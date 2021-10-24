""" Модуль для преобразования строк в нужные типы """

def str_to_int(val):
    """ Преобразование строки в int """
    res = None
    try:
        res = int(val)
    except:
        pass
    return res


def str_to_int_list_by_commas(val):
    """ Преобразование строки в список int """
    res = []
    try:
        buf = val.split(",")
        
        for elem in buf:
            try:
                res.append(int(elem))
            except ValueError:
                pass

        if len(res) == 0:
            res = None
    except:
        pass
    return res


def str_to_bool(val):
    """ Преобразование строки в bool """
    try:
        if val.lower().strip() == 'true':
            return True
    except:
        pass
    return False