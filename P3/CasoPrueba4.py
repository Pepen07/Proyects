import time  # Importar la biblioteca time
import os  # Importar la biblioteca os para manejo de archivos
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException

# Configuración de opciones para Chrome
chrome_options = Options()
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--disable-dev-shm-usage")

# Ruta al driver de Chrome
driver_service = Service('C:\\SeleniumDrivers\\chromedriver.exe')

# Inicializa el navegador
driver = webdriver.Chrome(service=driver_service, options=chrome_options)

try:
    # Abre la página de registro de facturas
    driver.get("http://localhost:3000/Factura.php")
    print("Página de registro de factura abierta")
    time.sleep(2)  # Espera de 2 segundos

    # Espera hasta que el campo de fecha esté presente y rellena la fecha actual
    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CSS_SELECTOR, "#fecha"))
    )
    fecha_field = driver.find_element(By.CSS_SELECTOR, "#fecha")
    fecha_field.send_keys("09/08/2024")  # Inserta la fecha deseada
    print("Fecha ingresada")
    time.sleep(2)  # Espera de 2 segundos

    # Ingresa el código del cliente
    codigo_cliente_field = driver.find_element(By.CSS_SELECTOR, "#codigo_cliente")
    codigo_cliente_field.send_keys("2022-1777")
    print("Código del cliente ingresado")
    time.sleep(2)  # Espera de 2 segundos

    # El nombre del cliente se completa automáticamente

    # Ingresa un comentario
    comentario_field = driver.find_element(By.CSS_SELECTOR, "#comentario")
    comentario_field.send_keys("Factura de prueba")
    print("Comentario ingresado")
    time.sleep(2)  # Espera de 2 segundos

    # El total a pagar se calcula automáticamente

    # Añade los artículos
    articulos = ["Masita", "cocacola", "leche"]
    cantidades = ["5", "1", "2"]

    for i, articulo in enumerate(articulos):
        # Añade un nuevo artículo si no es el primero
        if i > 0:
            add_articulo_button = driver.find_element(By.CSS_SELECTOR, "button[onclick='addArticulo()']")
            add_articulo_button.click()
            print(f"Artículo {articulo} agregado")
            time.sleep(2)  # Espera de 2 segundos

        # Encuentra el contenedor del artículo recién añadido
        articulos_container = driver.find_elements(By.CSS_SELECTOR, "#articulosContainer .articulo-item")[i]

        # Nombre del artículo
        nombre_articulo = articulos_container.find_element(By.CSS_SELECTOR, "input[name^='articulos'][name$='[nombre]']")
        nombre_articulo.send_keys(articulo)
        print(f"Nombre del artículo '{articulo}' ingresado")
        time.sleep(2)  # Espera de 2 segundos

        # Ingresa la cantidad del artículo
        cantidad_articulo = articulos_container.find_element(By.CSS_SELECTOR, "input[name^='articulos'][name$='[cantidad]']")
        cantidad_articulo.send_keys(cantidades[i])
        print(f"Cantidad del artículo '{articulo}' ingresada")
        time.sleep(2)  # Espera de 2 segundos

    # Opcional: Imprimir la factura
    print_button = driver.find_element(By.CSS_SELECTOR, ".no-print")
    print_button.click()
    print("Factura impresa")
    time.sleep(2)  # Espera de 2 segundos

    # Haz clic en el botón para registrar la factura
    registrar_button = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
    registrar_button.click()
    print("Factura registrada")
    time.sleep(2)  # Espera de 2 segundos

finally:
    # Cierra el navegador
    driver.quit()
