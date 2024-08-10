import time
import os
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.action_chains import ActionChains
from selenium.common.exceptions import NoSuchElementException

# Configuración de opciones para Chrome
chrome_options = Options()
chrome_options.add_argument("--no-sandbox")
chrome_options.add_argument("--disable-dev-shm-usage")

# Ruta al driver de Chrome
driver_service = Service('C:\\SeleniumDrivers\\chromedriver.exe')

# Inicializa el navegador
driver = webdriver.Chrome(service=driver_service, options=chrome_options)

def tomar_captura_pantalla(driver, nombre_archivo):
    """
    Toma una captura de pantalla y la guarda en el archivo especificado.

    :param driver: Instancia del navegador de Selenium.
    :param nombre_archivo: Ruta y nombre del archivo donde guardar la captura.
    """
    driver.save_screenshot(nombre_archivo)
    print(f"Captura de pantalla guardada en: {nombre_archivo}")

try:
    # Abre la página de estadísticas de facturación
    driver.get("http://localhost:3000/menu.php")
    print("Página de estadísticas de facturación abierta")
    time.sleep(2)  # Espera de 2 segundos

    # Lista de fechas para consultar
    fechas = [
        "01/08/2024",
        "06/08/2024",
        "09/08/2024"
    ]

    for fecha in fechas:
        # Encuentra y llena el campo de fecha
        fecha_field = driver.find_element(By.CSS_SELECTOR, "#fecha")
        fecha_field.clear()
        fecha_field.send_keys(fecha)
        print(f"Fecha ingresada: {fecha}")
        time.sleep(2)  # Espera de 2 segundos

        # Encuentra y hace clic en el botón de consulta
        submit_button = driver.find_element(By.CSS_SELECTOR, "form button[type='submit']")
        submit_button.click()
        print(f"Botón de consulta clickeado para la fecha: {fecha}")
        time.sleep(2)  # Espera de 2 segundos para los resultados

        # Verifica si los resultados se han mostrado
        try:
            resultado_card = driver.find_element(By.CSS_SELECTOR, ".card")
            print("Resultados de la consulta mostrados")

            # Desplázate hacia abajo para ver los resultados
            actions = ActionChains(driver)
            actions.move_to_element(resultado_card).perform()
            print("Desplazado hasta los resultados de la consulta")
            time.sleep(2)  # Espera de 2 segundos para observar los resultados

            # Toma una captura de pantalla y guarda en un archivo
            nombre_archivo = f"captura_{fecha.replace('/', '-')}.png"
            tomar_captura_pantalla(driver, nombre_archivo)

        except NoSuchElementException:
            print("No se encontraron resultados para la fecha: " + fecha)

finally:
    # Cierra el navegador
    driver.quit()
