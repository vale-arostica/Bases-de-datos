
connection, cursor = b.conectar()

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
        0 - EXIT
        """
	print(menu)
	print("---------------------------------------------------------------------------------------------------------\n")
	accion = input()
	if accion == '0' :
		break
	elif accion == '1':
		print("(Para volver al menú ingrese un guión: - )")
		print("Para crear una comuna ingrese el nombre de la región a la cual agregará la nueva comuna:")
		nombreg = input()
		if nombreg == '-':
			continue
		

	elif accion == '2':
		print("Para ")

	elif accion == '3':
		print("Para ")

	elif accion == '4':
		print("Para ")

	elif accion == '5':
		b.casostotalescom()

	elif accion == '6':
		print("Para ")

	elif accion == '7':
		print("Para ")

	elif accion == '8':
		print("Para ")

	elif accion == '9':
		print("Para ")

	elif accion == '10':
		print("Para ")

	elif accion == '11':
		print("Para ")

	elif accion == '12':
		print("Para ")
	else:
		print("Accion inválida")
		time.sleep(1)
		continue