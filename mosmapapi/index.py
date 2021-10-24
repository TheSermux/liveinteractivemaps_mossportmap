#!/usr/bin/python3.8
""" cgi-скрипт обработки запросов от сервера """

import sys
import json
import cgi

from converts import *
from mos_hack import get_backend_response

# обработка параметров запроса
fs = cgi.FieldStorage()

result = None
try:
    name = fs.getfirst('name')
    zone_name = fs.getfirst('zone_name')
    load_refs = str_to_bool(fs.getfirst('load_refs'))
    load_population_map = str_to_bool(fs.getfirst('load_population_map'))
    id_avs = str_to_int_list_by_commas(fs.getfirst('id_avs'))
    id_deps = str_to_int_list_by_commas(fs.getfirst('id_deps'))
    id_zts = str_to_int_list_by_commas(fs.getfirst('id_zts'))
    id_sts = str_to_int_list_by_commas(fs.getfirst('id_sts'))
    limit = str_to_int(fs.getfirst('limit'))

    result = get_backend_response(load_refs,None,name,id_avs,id_deps,zone_name,id_zts,id_sts,None,limit,load_population_map)
except:
    result = { 'error': 'Произошла ошибка'}

# Формирование ответа
print("Content-Type: application/json")
print("\n")
print("\n")

print(json.dumps(result,ensure_ascii=False))
print("\n")

sys.stdout.close()
