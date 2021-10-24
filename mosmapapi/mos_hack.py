""" Модуль с основной логикой проекта """
import sqlite3
import json
import os.path

from turfpy.measurement import boolean_point_in_polygon, area
from geojson import Point, Polygon, Feature



# путь к текущей директории, чтобы открыть нужный файл sqlite
base_dir = os.path.dirname(os.path.abspath(__file__))
db_path = os.path.join(base_dir, "mos_hack.sqlite")


def get_backend_response(load_refs:bool=False,id_fac:int=None,name:str=None,id_avs:list=None,
             id_deps:list=None,zone_name:str=None,id_zts:list=None,id_sts:list=None,
             polygon:list=None,limit:int=None,load_population_map:bool=False):
    """ Функция получения ответа от бэкенда """
    with sqlite3.connect(db_path) as db:
        # настраиваем преобразование выборки к словарю
        db.row_factory = sqlite3.Row
        result = dict()
        
        if load_refs:
            result['reference_data'] = {
                'availabilities': get_reference_data_dict(db,REF_NUM_AVAILABILITY),
                'departments': get_reference_data_dict(db,REF_NUM_DEPARTMENT),
                'sport_types': get_reference_data_dict(db,REF_SPORT_TYPE),
                'zone_types': get_reference_data_dict(db,REF_ZONE_TYPE)
            }
        
        if limit is None or limit > 0:
            facilities,analytics = get_facilities_and_analytics(db,id_fac,name,id_avs,id_deps,zone_name,id_zts,id_sts,polygon,limit)
            result['facilities'] = facilities
            result['analytics'] = analytics
        
        if load_population_map:
            result['population_map'] = get_population_map()

        return result


def get_facilities_and_analytics(db,id_fac:int=None,name:str=None,id_av_list:list=None,id_dep_list:list=None,
                        zone_name:str=None,id_zt_list:list=None,id_st_list:list=None,polygon:list=None,
                        limit:int=None):
    """ Функция получения массива объектов и аналитики по ним """
    cursor = db.cursor()
    where_conditions = ''
    is_polygon_given = polygon is not None and len(polygon) > 2

    # выбор конкретного объекта
    if id_fac is not None:
        where_conditions += f" and id_fac={id_fac}"

    # выбор объектов по условиям фильтра
    else:
        if name is not None and len(name) > 0:
            where_conditions += f" and lower(name) like lower('%{name.lower()}%')"

        if id_av_list is not None and len(id_av_list) > 0:
            where_conditions += f" and id_av in ({list_to_string(id_av_list)})"
        
        if id_dep_list is not None and len(id_dep_list) > 0:
            where_conditions += f" and id_dep in ({list_to_string(id_dep_list)})"
        
        if zone_name is not None and len(zone_name) > 0:
            where_conditions += f" and id_fac in (select distinct id_fac from zones_tbl where lower(name) like lower('%{zone_name.lower()}%'))"
        
        if id_zt_list is not None and len(id_zt_list) > 0:
            where_conditions += f" and id_fac in (select distinct id_fac from zones_tbl where id_zt in ({list_to_string(id_zt_list)}))"
        
        if id_st_list is not None and len(id_st_list) > 0:
            where_conditions += f" and id_fac in (select distinct id_fac from zones_tbl where id_zone in (select distinct id_zone from zone_sport_type_tbl where id_st in ({list_to_string(id_st_list)})))"
        
        # 1-й этап фильтрации по полигону: выбираем точки в пределах min и max значений lat и lng
        if is_polygon_given:
            max_lat=max(list(map(lambda x : x[0],polygon)))
            min_lat=min(list(map(lambda x : x[0],polygon)))
            max_lng=max(list(map(lambda x : x[1],polygon)))
            min_lng=min(list(map(lambda x : x[1],polygon)))
            where_conditions += f" and lat between {min_lat} and {max_lat} and lng between {min_lng} and {max_lng}"
        
        if limit is not None and limit > 0:
             where_conditions += f" limit {limit}"

    # запрос спортивных объектов
    cursor.execute("select *" + BASIC_FACILITY_SELECT + where_conditions)
    facilities = [dict(row) for row in cursor.fetchall()]

    sum_zone_area_m2,polygon_area_km2,count_zones = 0,0,0
    id_st_set,id_fac_list_internal=set(),[]

    # 2-й этап фильтрации по полигону: применяем к выбранным точкам функцию определения точек внутри полигона
    if is_polygon_given :
        geo_polygon = Polygon([polygon])
        polygon_area_km2 = area(geo_polygon) / 1000000

        for fac in facilities[::]:
            point = dict_to_geopoint(fac)
            if not boolean_point_in_polygon(point, geo_polygon):
                facilities.remove(fac)
        
    for fac in facilities:
        # считаем суммарную площадь выбранных объектов
        sum_zone_area_m2 += fac['sum_area']
        
        id_fac_list_internal.append(fac['id_fac'])

        # для каждого объекта выбираем зоны
        cursor.execute(f"select id_zone,name,type,area,id_zt from zones_tbl where id_fac = {fac['id_fac']}")
        zones = [dict(row) for row in cursor.fetchall()]
        count_zones += len(zones)
        
        # для каждой зоны выбираем виды спорта
        for z in zones:
            cursor.execute(f"select id_st,st from zone_sport_type_tbl where id_zone = {z['id_zone']}")
            sport_types = [dict(row) for row in cursor.fetchall()]
            z['sport_types'] = sport_types

            # сбор уникальных id_st
            for st in sport_types:
                id_st_set.add(st['id_st'])
        
        fac['zones'] = zones

    dist_sport_types = get_reference_data_dict(db,REF_SPORT_TYPE,list(id_st_set))
    sum_zone_area_per_person_m2 = 0

    # определяем плотность населения (используем до 5 объектов, проходимся по всем районам)
    population_density = AVG_MOSCOW_POPULATION_DENSITY
    for fac in facilities[:5]:
        district_population_density = get_population_density_for_district_by_point(fac['lat'],fac['lng'])

        if district_population_density is not None:
            population_density = district_population_density
            break

    if is_polygon_given and polygon_area_km2 != 0:
        sum_zone_area_per_person_m2 = sum_zone_area_m2 / (polygon_area_km2 * population_density)
    
    # считаем количество зон по видам
    cursor.execute(f"select z.id_zt, max(z.type) as type, count(*) as cnt from zones_tbl z where z.id_fac in({list_to_string(id_fac_list_internal)}) group by z.id_zt order by 3 desc")
    zone_type_count = [dict(row) for row in cursor.fetchall()]

    analytics = {
        'sum_zone_area_m2': sum_zone_area_m2,
        'sum_zone_area_per_person_m2': sum_zone_area_per_person_m2,
        'dist_sport_types': dist_sport_types,
        'count_zones': count_zones,
        'zone_type_count': zone_type_count
    }
    # print('population_density = ',str(population_density))
    # print('count =',len(facilities))
    return facilities,analytics


def get_reference_data_dict(db,ref_num=None,ids=None):
    """ Функция получения данных из словаря """
    cursor = db.cursor()
    where_condition = ''

    if ids is not None:
        where_condition = f' where {REFERENCE_IDS[ref_num]} in ({list_to_string(ids)})'

    cursor.execute(f"select * from {REFERENCE_TABLES[ref_num]}" + where_condition + " order by 1")
    result = [dict(row) for row in cursor.fetchall()]
    return result


def get_population_map():
    """ Функция получения тепловой карты населения """
    file_path = os.path.join(base_dir, "moscow_population.geojson")

    with open(file_path,encoding="utf-8") as json_file:
        population_map = json.load(json_file)
        return population_map


def get_population_density_for_district_by_point(lat,lng):
    """ Функция определения плотности населения в районе, в который входит
        переданная точка. Если не удаётся определить, какому району принадлежит
        точка, возвращается None
    """
    districts = get_population_map()['districts']
    point = Feature(geometry=Point((lat,lng)))

    for district in districts:
        geo_polygon = Polygon([district['geometry']['coordinates'][0][0]])

        if boolean_point_in_polygon(point,geo_polygon):
            return district['population_density']

    return None


def list_to_string(values:list):
    """ Функция преобразования списка в строку, разделённую запятой """
    return ','.join(list(map(str,values)))


def dict_to_geopoint(data:dict):
    """ Преобразование словаря в Point-geojson """
    return Feature(geometry=Point((data['lat'],data['lng'])))


def write_to_debug_file(data):
    """ Функция вывода данных в debug.txt """
    with open(os.path.join(base_dir, "debug.txt"), 'w', encoding='utf8') as json_file:
        json.dump(data, json_file, ensure_ascii=False)



BASIC_FACILITY_SELECT = " from facilities_tbl where id_av is not null and id_dep is not null and sum_area is not null"

REFERENCE_TABLES = ['availability_tbl','departments_tbl','sport_type_tbl','zone_type_tbl']
REFERENCE_IDS = ['id_av','id_dep','id_st','id_zt']

REF_NUM_AVAILABILITY = 0
REF_NUM_DEPARTMENT = 1
REF_SPORT_TYPE= 2
REF_ZONE_TYPE= 3

AVG_MOSCOW_POPULATION_DENSITY = 4941.45


# тестирование фильтрации
# write_to_debug_file(get_backend_response(polygon=polygon,id_sts=[2,3],id_avs=[1,2],id_deps=[237332,266600],name='ПАРК',zone_name='ТРАССА',limit=3))

# polygon = get_population_map()['districts'][5]['geometry']['coordinates'][0][0]
# write_to_debug_file(get_backend_response(polygon=polygon))

# example_polygon = get_population_map()['districts'][5]['geometry']['coordinates'][0][0]
# write_to_debug_file(get_backend_response(load_refs=True,load_population_map=True,name='спорт',zone_name='спорт',id_deps=[219165,237387],id_avs=[3,4],id_sts=[4,14,66,83],id_zts=[32,34],polygon=example_polygon))
