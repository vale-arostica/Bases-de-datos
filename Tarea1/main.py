import cx_Oracle
from prettytable import PrettyTable
import time

connection = cx_Oracle.connect("varo", "123", "localhost:1521")
print("Database version:", connection.version)
cursor = connection.cursor()

cursor.execute (
    """
        DECLARE
        BEGIN
            EXECUTE IMMEDIATE 
                'CREATE TABLE Casos_por_comuna(
                    Comuna VARCHAR(80) NOT NULL,
                    Codigo_comuna INTEGER NOT NULL,
                    Poblacion INTEGER NOT NULL,
                    Casos_confirmados INTEGER NOT NULL,
                    PRIMARY KEY(Codigo_comuna)
                )';
            EXCEPTION WHEN OTHERS THEN
                IF SQLCODE = -955 then null; 
                ELSE raise; 
                END IF;
        END;
    """
)

cursor.execute (
    """
        DECLARE
        BEGIN
            EXECUTE IMMEDIATE
            'CREATE TABLE Comunas_region(
                Region VARCHAR(50),
                Codigo_region INTEGER NOT NULL,
                Codigo_comuna INTEGER NOT NULL
            )';
            EXCEPTION WHEN OTHERS THEN
                IF SQLCODE = -955 then null; ELSE raise; END IF;
        END;

    """
)

select_cpc = "SELECT COUNT(*) AS datos FROM casos_por_comuna" # Donde cpc son las siglas del nombre de la tabla
select_cr = "SELECT COUNT(*) AS datos FROM comunas_region"  # Esta sentencia cuenta el total de filas. cr siglas de tabla comunas_region

# Extraemos de cursor el número que obtenemos al contar filas con las sentencias select_cpc y select_cr 
data_cpc = cursor.execute(select_cpc).fetchall()[0][0] # [(NRO,)] 
data_cr = cursor.execute(select_cr).fetchall()[0][0] 

#INSERTAR VALORES EN COMUNAS_REGION SOLO SI NO HABÍAN VALORES ANTES; NRO FILAS CPC = 0
if data_cpc == 0:
    RCfile = open('RegionesComunas.csv', 'rt', encoding='utf-8')
    for row in RCfile:
        Reg, cod_reg, cod_com = row.strip().split(',')
        if cod_reg == 'Codigo region': continue
        Reg = "'"  +Reg + "'"
        Cadena= """ INSERT INTO Comunas_region (Region, Codigo_region, Codigo_comuna) 
                        VALUES ({}, {}, {})
                """.format(Reg, cod_reg, cod_com)

        cursor.execute(Cadena)
    RCfile.close()

#INSERTAR VALORES EN CASOS_POR_COMUNA SOLO SI NO HABÍAN VALORES ANTES; NRO FILAS CR = 0
if data_cr == 0:
    CasosPCfile = open('CasosConfirmadosPorComuna.csv', 'rt', encoding='utf-8')
    for row in CasosPCfile:
        comuna, cod_com, poblacion, casos = row.strip().split(',')
        if cod_com == 'Codigo comuna': continue
        comuna = "'"+comuna+"'"
        cadena2 = """INSERT INTO Casos_por_comuna
                        VALUES ({},{},{},{})
                """.format(comuna, cod_com, poblacion, casos)
        cursor.execute(cadena2)
    CasosPCfile.close()

cursor.execute (
    """
        DECLARE
        BEGIN
            EXECUTE IMMEDIATE
            'CREATE VIEW regiones AS (
                SELECT 
                    cr.region,
                    cr.codigo_region,
                    cpc.poblacion,
                    cpc.casos_confirmados
                FROM casos_por_comuna cpc JOIN comunas_region cr
                    ON cpc.codigo_comuna = cr.codigo_comuna
            )';
            EXCEPTION WHEN OTHERS THEN
                IF SQLCODE = -955 then null; ELSE raise; END IF;
        END;
    """
)

cursor.execute (
    """
        DECLARE
        BEGIN
            EXECUTE IMMEDIATE
            'CREATE TABLE casos_por_region AS (
                SELECT
                    region,
                    codigo_region,
                    SUM(poblacion) AS POBLACION,
                    SUM(CASOS_CONFIRMADOS) AS CASOS_CONFIRMADOS
                FROM regiones
                GROUP BY codigo_region, region
            )';
            EXCEPTION WHEN OTHERS THEN
                IF SQLCODE = -955 then null; ELSE raise; END IF;
        END;
    """
)

###################################################################################################################################################
###################################################################################################################################################
###################################################################################################################################################


def crearcomuna(nomb, reg): # nombre de la nueva comuna, region a la que pertenece, población, casos.
    stmt1= """SELECT * FROM casos_por_region 
            WHERE region = '{}'""".format(reg)
    cursor.execute(stmt1)
    datos = cursor.fetchall()
    if len(datos) == 0:
        print("Región no encontrada. Se recomienda verificar nombre o crear nueva región.\n")
        return
    cod_r = datos[0][1]
    stmt2 = """SELECT MAX(CODIGO_COMUNA) 
                FROM casos_por_comuna 
                WHERE codigo_comuna < {}
            """.format(((cod_r*1000)+1000))
    cursor.execute(stmt2)
    cod_c = (cursor.fetchall()[0][0])+1
    updatecpc= """
            INSERT INTO casos_por_comuna (
                comuna, 
                codigo_comuna, 
                poblacion, 
                casos_confirmados
            )
            VALUES ('{}',{},{},{})
            """.format(nomb, cod_c ,0, 0)
    cursor.execute(updatecpc)
    print("Comuna creada con éxito\n")
    return


def crearregion(nomb): # nombre comuna
    stmt1= """SELECT * FROM casos_por_region 
            WHERE region = '{}'""".format(nomb)
    cursor.execute(stmt1)
    datos = cursor.fetchall()
    if len(datos) != 0:
        print("La región ya existe.\n")
        return
    stmt2 = """
            SELECT MAX(codigo_region) 
            FROM casos_por_region
            """
    newcodr = cursor.execute(stmt2).fetchall()[0][0]+1
    updatecpr= """
            INSERT INTO casos_por_region (
                region, 
                codigo_region, 
                poblacion, 
                casos_confirmados
            )
            VALUES ('{}',{},{},{})
            """.format(nomb, newcodr ,0, 0)
    cursor.execute(updatecpr)
    print("Region creada con éxito\n")
    return
    
def casoscom(cod): 
    stmt1 = """
            SELECT comuna, casos_confirmados
            FROM casos_por_comuna
            WHERE codigo_comuna = {}
            """.format(cod)
    com , casos = cursor.execute(stmt1).fetchone()
    print("Casos totales de {} = {}\n".format(com, casos))
    return 

def casosreg(cod): 
    stmt1 = """
            SELECT region, casos_confirmados
            FROM casos_por_region
            WHERE codigo_region = {}
            """.format(cod)
    reg, casos = cursor.execute(stmt1).fetchone()
    print("Casos totales de {} = {}\n".format(reg, casos))
    return 
    
def casostotalescom(): #Printea como tabla comunas-casos
    my_table = PrettyTable()
    my_table.field_names = ["Comuna", "Codigo comuna", "Casos Confirmados"]
    stmt = """
            SELECT comuna, codigo_comuna, casos_confirmados
            FROM casos_por_comuna ORDER BY comuna
            """
    for row in cursor.execute(stmt).fetchall():
        my_table.add_row(row)
    print(my_table)


def casostotalesreg(): #printea como tabla regiones-casos
    my_table = PrettyTable()
    my_table.field_names = ["Region","Codigo region", "Casos Confirmados"]
    stmt = """
            SELECT region, codigo_region, casos_confirmados
            FROM casos_por_region ORDER BY region
            """
    for row in cursor.execute(stmt).fetchall():
        my_table.add_row(row)
    print(my_table)

def actualizarcasoscom(com, n): #n nuevos casos confirmados a la comuna com. OBS: n puede ser negativo o positivo y puede ser string o int.
    stmt1 = """
            SELECT comuna, casos_confirmados
            FROM casos_por_comuna
            WHERE codigo_comuna = {}
            """.format(com)
    nombre, newcasos = cursor.execute(stmt1).fetchone()
    newcasos = newcasos + int(n)
    stmt2 = """
            UPDATE casos_por_comuna
            SET casos_confirmados = {}
            WHERE codigo_comuna = {}
            """.format(newcasos, com)
    cursor.execute(stmt2)
    codreg = com//1000
    stmt4 = """SELECT casos_confirmados FROM casos_por_regionn WHERE codigo_region ={}""".format(codreg)
    casr = cursor.execute(stmt4).fetchone()[0] + int(n)
    stmt3 = """
			UPDATE casos_por_region
			SET casos_confirmados = {}
			WHERE codigo_region = {}
			""".format(casr,codreg)
    cursor.execute(stmt3)
    time.sleep(1)
    print("Casos confirmados de {} actualizados.".format(nombre))
    time.sleep(1)
    print("{} : {} casos confirmados".format(nombre, newcasos))
    time.sleep(1)
    return


cursor.execute(
    """
        DECLARE
        BEGIN
            EXECUTE IMMEDIATE
            'CREATE VIEW tasa_casos_comunas AS (
                SELECT
                    comuna,
                    casos_confirmados/poblacion AS tasa_de_contagio
                FROM casos_por_comuna
            )';
            EXCEPTION WHEN OTHERS THEN
                IF SQLCODE = -955 then null; ELSE raise; END IF;
        END;
    """)
            
cursor.execute(
    """
        DECLARE
        BEGIN
            EXECUTE IMMEDIATE
            'CREATE VIEW tasa_casos_regiones AS (
                SELECT
                    region,
                    casos_confirmados/poblacion AS tasa_de_contagio
                FROM casos_por_region
            )';
            EXCEPTION WHEN OTHERS THEN
                IF SQLCODE = -955 then null; ELSE raise; END IF;
        END;
    """)


def top5com():
    my_table = PrettyTable()
    my_table.field_names = ["Comuna", "Porcentaje de casos"]
    data = cursor.execute("""
        SELECT * FROM tasa_casos_comunas ORDER BY tasa_de_contagio DESC
    """).fetchmany(5)
    for elem in data:
        my_table.add_row(elem)
    print(my_table)


def top5reg():
    my_table = PrettyTable()
    my_table.field_names = ["Region", "Porcentaje de casos"]
    data = cursor.execute("""
        SELECT * FROM tasa_casos_regiones ORDER BY tasa_de_contagio DESC
    """).fetchmany(5)
    for elem in data:
        my_table.add_row(elem)
    print(my_table)


def combinarcom(newname, cod1, cod2): #Se combinarán las comunas en la cod2
    cod1 = int(cod1)
    cod2 = int(cod2)
    stmt1 = """
            SELECT *
            FROM casos_por_comuna
            WHERE codigo_comuna = {} OR codigo_comuna = {}
        """.format(cod1, cod2)
    data = cursor.execute(stmt1).fetchall()
    pob1, pob2 = data[0][2], data[1][2]
    casos1, casos2 = data[0][3], data[1][3]
    reg1 = cod1//1000
    reg2 = cod2//1000
    if reg1 != reg2:
        #resto los habitantes y casos de la reg 1
        stt1 = """
                UPDATE casos_por_region
                SET casos_confirmados = casos_confirmados- {},
                poblacion = poblacion- {}
                WHERE codigo_region = {}
                """.format(casos1, pob1, reg1)
        cursor.execute(stt1)
        #sumo los habitantes y casos a la reg 2
        stt2 = """
                UPDATE casos_por_region
                SET casos_confirmados = casos_confirmados+{},
                poblacion = poblacion+ {}
                WHERE codigo_region = {}
                """.format(casos1, pob1, reg2)
        cursor.execute(stt2)
    stmt2 = """
            UPDATE casos_por_comuna
            SET comuna = '{}',
                poblacion = poblacion + {},
                casos_confirmados = casos_confirmados+{}
            WHERE codigo_comuna = {}
        """.format(newname, pob1, casos1, cod2)
    cursor.execute(stmt2)
    stmt3 = """
            DELETE casos_por_comuna
            WHERE codigo_comuna = {}
        """.format(cod1)
    cursor.execute(stmt3)


def combinarreg(newname, cod1, cod2): #Se combinarán las regiones en la cod2
    cod1 = int(cod1)
    cod2 = int(cod2)
    stmt1 = """
            SELECT *
            FROM casos_por_region
            WHERE codigo_region = {} OR codigo_region = {}
        """.format(cod1, cod2)
    data = cursor.execute(stmt1).fetchall()
    newpob = data[0][2]+data[1][2]
    newcasos = data[0][3]+data[1][3]
    stmt2 = """
            UPDATE casos_por_region
            SET region = '{}',
                poblacion = {},
                casos_confirmados = {}
            WHERE codigo_region = {}
        """.format(newname, newpob, newcasos, cod2)
    cursor.execute(stmt2)
    stt1 = """
            UPDATE casos_por_comuna
            SET codigo_comuna = MOD(codigo_comuna,1000) + {} +111
            WHERE codigo_comuna > {} AND codigo_comuna < {} 
            """.format(cod2*1000, cod1*1000, (cod1*1000+1000))
    cursor.execute(stt1)
    stmt3 = """
            DELETE casos_por_region
            WHERE codigo_region = {}
        """.format(cod1)
    cursor.execute(stmt3)   

def extirparregiones():
    contador = 0
    regiones = []
    stmt1 =  """
            SELECT region, codigo_region FROM casos_por_region
                WHERE region IN (SELECT region FROM tasa_casos_regiones
                                    WHERE tasa_de_contagio > 0.15)
            """
    data = cursor.execute(stmt1).fetchall()
    for row in data:
        contador += 1
        n_reg , codig = row[0], int(row[1])
        regiones.append(n_reg)
        stt = """
                DELETE casos_por_comuna
                WHERE codigo_comuna > {} AND codigo_comuna < {}
                """.format(codig*1000, (codig*1000)+1000)
        cursor.execute(stt)
        stt1 = """DELETE casos_por_region
                    WHERE codigo_region = {}""".format(codig)
        cursor.execute(stt1)
    return contador, regiones





##############################################################################################################################################
##############################################################################################################################################
##############################################################################################################################################

def tablacodigoscom():
	print("Comunas por orden alfabético:")
	time.sleep(1)
	s = """SELECT comuna, codigo_comuna FROM casos_por_comuna ORDER BY comuna ASC"""
	my_table = PrettyTable()
	my_table.field_names = ["Comunas", "Codigo"]
	for row in cursor.execute(s).fetchall():
		my_table.add_row(row)
	print(my_table)
	print("Ingrese código comuna")

def tablacodigosreg():
	print("Regiones por orden alfabético:")
	time.sleep(1)
	s = """SELECT region, codigo_region FROM casos_por_region ORDER BY region ASC"""
	my_table = PrettyTable()
	my_table.field_names = ["Regiones", "Codigo"]
	for row in cursor.execute(s).fetchall():
		my_table.add_row(row)
	print(my_table)
	print("Ingrese código región")

print("\nBIENVENIDO AL MENÚ DE INTERACCIÓN CON BASE DE DATOS DE APOYO AL CONTROL DE PANDEMIA COVID-19, CHILE.\n")
accion = 1000
while accion != 0: 
	print("---------------------------------------------------------------------------------------------------------\n")
	print("Ingrese el número de la acción que desea realizar. Para terminar la interacción con la base de datos ingrese 0\n")
	menu =  """
        1 - Crear comuna
        2 - Crear región
        3 - Ver casos totales de una comuna
        4 - Ver casos totales de una región
        5 - Ver casos totales de TODAS las comunas
        6 - Ver casos totales de TODAS las regiones
        7 - Agregar nuevos casos a una comuna
        8 - Eliminar casos nuevos de una comuna
        9 - Combinar comunas
        10 - Cominar regiones
        11 - Ranking 5 comunas con mayor porcentaje de casos respecto a su población
        12 - Ranking 5 regiones con mayor porcentaje de casos respecto a su población
        13 - Extirpar regiones con positividad mayor a 15%
        0 - EXIT
        """
	print(menu)
	print("---------------------------------------------------------------------------------------------------------\n")
	accion = input()

	if accion == '0' :
		break

	elif accion == '1':
		print("(Para volver al menú ingrese un guión: - )")
		print("Para crear una comuna ingrese el nombre de la REGIÓN a la cual agregará la nueva comuna:")
		nombreg = input()
		if nombreg == '-':
			continue
		print("Ingrese el nombre de la nueva COMUNA:")
		nombrecom = input()
		if nombrecom == '-':
			continue
		crearcomuna(nombreg, nombrecom)

	elif accion == '2':
		print("(Para volver al menú ingrese un guión: - )")
		print("Ingrese el nombre de la nueva REGION")
		nombrereg = input()
		if nombreg == '-':
			continue
		crearregion(nombrereg)

	elif accion == '3':
		print("(Para volver al menú ingrese un guión: - )")
		print("Para ver los casos totales de una comuna ingrese a continuación el código de la comuna.")
		print("Si no sabe el código ingrese un asterisco: * para ver las comunas y sus códigos.")
		codigo = input()
		if codigo == '*':
			tablacodigoscom()
			codigo = input()
		if codigo == '-':
			continue
		casoscom(codigo)

	elif accion == '4':
		print("(Para volver al menú ingrese un guión: - )")
		print("Para ver los casos totales de una región ingrese a continuación el código de la comuna.")
		print("Si no sabe el código ingrese un asterisco: * para ver las regiones y sus códigos.")
		codigo = input()
		if codigo == '*':
			tablacodigosreg()
			codigo = input()
		if codigo == '-':
			continue
		casosreg(codigo)

	elif accion == '5':
		casostotalescom()

	elif accion == '6':
		casostotalesreg()

	elif accion == '7':
		print("(Para volver al menú ingrese un guión: - )")
		print("Ingrese el código de la comuna de la cual aumentaron los casos.")
		print("Si no sabe el código ingrese un asterisco: * para ver las comunas y sus códigos.")
		codigo = input()
		if codigo == '*':
			tablacodigoscom()
			codigo = input()
		if codigo == '-':
			continue
		print("Ingrese la cantidad de casos nuevos:")
		casos = input()
		actualizarcasoscom(codigo, casos)

	elif accion == '8':
		print("(Para volver al menú ingrese un guión: - )")
		print("Ingrese el código de la comuna de la cual disminuyeron los casos.")
		print("Si no sabe el código ingrese un asterisco: * para ver las comunas y sus códigos.")
		codigo = input()
		if codigo == '*':
			tablacodigoscom()
			codigo = input()
		if codigo == '-':
			continue
		print("Ingrese la cantidad de casos disminuídos:")
		casos = '-'+input()
		actualizarcasoscom(codigo, casos)

	elif accion == '9':
		print("(Para volver al menú ingrese un guión: - )")
		print("Ingrese el nuevo nombre de la comuna:")
		new = input()
		if new == '-':
			continue
		print("Ingrese el código de la comuna 1:")
		print("Si no sabe el código ingrese un asterisco: * para ver las comunas y sus códigos.")
		codigo1 = input()
		if codigo1 == '*':
			tablacodigoscom()
			codigo1 = input()
		time.sleep(1)
		print("Ingrese el código de la comuna 2:")
		time.sleep(1)
		codigo2 = input()
		time.sleep(1)
		print("¿En cuál comuna desea combinar las comunas: 1 o 2?")
		time.sleep(1)
		eleccion = input()
		if eleccion == '1':
			combinarcom(new, codigo2, codigo1)
			time.sleep(1)
			print("Comunas combinadas exitosamente.")
			time.sleep(1)
		elif eleccion == '2':
			combinarcom(new, codigo1, codigo2)
			time.sleep(1)
			print("Comunas combinadas exitosamente.")
			time.sleep(1)
		else:
			time.sleep(1)
			print("Ocurrió un error porfavor vuelva a intentarlo")
			time.sleep(1)

	elif accion == '10':
		print("(Para volver al menú ingrese un guión: - )")
		print("Ingrese el nuevo nombre de la región:")
		new = input()
		if new == '-':
			continue
		print("Ingrese el código de la región 1:")
		print("Si no sabe el código ingrese un asterisco: * para ver las regiones y sus códigos.")
		codigo1 = input()
		if codigo1 == '*':
			tablacodigosreg()
			codigo1 = input()
		time.sleep(1)
		print("Ingrese el código de la región 2:")
		time.sleep(1)
		codigo2 = input()
		time.sleep(1)
		print("¿En cuál región desea combinar las regiones: 1 o 2?")
		time.sleep(1)
		eleccion = input()
		if eleccion == '1':
			combinarreg(new, codigo2, codigo1)
			time.sleep(1)
			print("Regiones combinadas exitosamente.")
			time.sleep(1)
		elif eleccion == '2':
			combinarreg(new, codigo1, codigo2)
			time.sleep(1)
			print("Regiones combinadas exitosamente.")
			time.sleep(1)
		else:
			time.sleep(1)
			print("Ocurrió un error porfavor vuelva a intentarlo")
			time.sleep(1)

	elif accion == '11':
		top5com()

	elif accion == '12':
		top5reg()

	elif accion == '13':
		print("Se eliminarán las regiones con mayor positividad (Tasa > 0.15):\n")
		time.sleep(1)
		top5reg()
		time.sleep(1)
		print("Eliminando...\n")
		contador , regiones = extirparregiones()
		time.sleep(1)
		print("Se han eliminado {} regiones:\n".format(contador))
		for elem in regiones:
			print(elem)
		time.sleep(1)

	else:
		print("Accion inválida")
		time.sleep(1)
		continue


connection.commit()
connection.close()