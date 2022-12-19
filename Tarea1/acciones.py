

def crearcomuna(nombre, region, pobla, casos):
    
    cursor.execute("""SELECT * FROM casos_por_region WHERE region = 'Coquimbolas'""")
    print(cursor.fetchall())


    updatecpc= """
            INSERT INTO casos_por_comunas (
                comuna, 
                codigo_comuna, 
                poblacion, 
                casos_confirmados
            )
            VALUES ({},{},{},{})
            """.format(nombre, cod_c ,pobla, casos)

    updatecr= """
            INSERT INTO comunas_region (
                region,
                codigo_region,
                codigo_comuna
            )
            VALUES ({},{},{})
         """.format(region, cod_r, cod_c)

