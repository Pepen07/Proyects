import time  # Importar la biblioteca time
import os  # Importar la biblioteca os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By

# Configuración de opciones para Chrome
chrome_options = Options()
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--disable-dev-shm-usage")

# Ruta al driver de Chrome
driver_service = Service('C:\\SeleniumDrivers\\chromedriver.exe')

# Inicializa el navegador
driver = webdriver.Chrome(service=driver_service, options=chrome_options)

def add_articulos_to_invoice(driver, articulos, cantidades):
    """
    Añade una lista de artículos y cantidades a la factura.

    :param driver: Instancia del navegador de Selenium.
    :param articulos: Lista de nombres de artículos.
    :param cantidades: Lista de cantidades correspondientes a los artículos.
    """
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
        time.sleep(3)  # Espera de 3 segundos

def tomar_captura_pantalla(driver, nombre_archivo):
    """
    Toma una captura de pantalla y la guarda en el archivo especificado.

    :param driver: Instancia del navegador de Selenium.
    :param nombre_archivo: Ruta y nombre del archivo donde guardar la captura.
    """
    if not os.path.exists('fotos'):
        os.makedirs('fotos')  # Crea la carpeta 'fotos' si no existe

    archivo_captura = os.path.join('fotos', nombre_archivo)
    driver.save_screenshot(archivo_captura)
    print(f"Captura de pantalla guardada en: {archivo_captura}")

try:
    # Abre la página de registro de facturas
    driver.get("http://localhost:3000/Factura.php")
    print("Página de registro de factura abierta")
    time.sleep(2)  # Espera de 2 segundos

    # Añade los artículos y cantidades
    articulos = ["cocacola", "galletas emperador", "masita"]
    cantidades = ["1", "3", "4"]
    add_articulos_to_invoice(driver, articulos, cantidades)

    # Toma una captura de pantalla antes de finalizar
    tomar_captura_pantalla(driver, 'factura_completa.png')

finally:
    # Cierra el navegador
    driver.quit()
