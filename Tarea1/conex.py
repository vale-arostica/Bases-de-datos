import cx_Oracle

connection = cx_Oracle.connect("varo", "123", "localhost:1521")
print("Database version:", connection.version)
cursor = connection.cursor()

cursor.execute (
    """
        CREATE TABLE Casos_por_comuna (
            Comuna VARCHAR(100) NOT NULL,
            Codigo_comuna INTEGER NOT NULL,
            Poblacion INTEGER NOT NULL,
            Casos_confirmados INTEGER NOT NULL,
            PRIMARY KEY(Codigo_comuna)
        )
    """
)

cursor.execute (
    """
        DECLARE
        BEGIN
            EXECUTE IMMEDIATE 'CREATE TABLE Comunas_region(
                Region VARCHAR(50),
                Codigo_region INTEGER NOT NULL,
                Codigo_comuna INTEGER NOT NULL,
            )';
            EXCEPTION WHEN OTHERS THEN
                IF SQLCODE = -955 THEN NULL; ELSE RAISE; END IF;
        END;
    """
)
connection.close()