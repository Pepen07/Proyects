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

def take_screenshot(driver, description):
    """Toma una captura de pantalla y la guarda en el directorio 'fotos'."""
    # Crea un directorio para guardar las capturas de pantalla si no existe
    screenshot_dir = "fotos"
    if not os.path.exists(screenshot_dir):
        os.makedirs(screenshot_dir)

    # Genera un nombre de archivo único para la captura
    screenshot_name = f"{screenshot_dir}/{description}_screenshot_{int(time.time())}.png"

    # Toma la captura de pantalla y la guarda en el archivo
    driver.save_screenshot(screenshot_name)
    print(f"Captura de pantalla guardada como {screenshot_name}")

try:
    # Abre la página de registro de facturas
    driver.get("http://localhost:3000/Factura.php")
    print("Página de registro de factura abierta")
    time.sleep(2)  # Espera de 2 segundos

    # Lista de códigos de cliente
    codigos_cliente = ["2022-1759", "2022-1777"]

    for codigo in codigos_cliente:
        # Ingresa el código del cliente
        codigo_cliente_field = driver.find_element(By.CSS_SELECTOR, "#codigo_cliente")
        
        # Limpia el campo antes de ingresar un nuevo código
        codigo_cliente_field.clear()
        codigo_cliente_field.send_keys(codigo)
        print(f"Código del cliente {codigo} ingresado")
        time.sleep(2)  # Espera de 2 segundos

        # El nombre del cliente se completa automáticamente

        comentario_field = driver.find_element(By.CSS_SELECTOR, "#comentario")
        
        # Limpia el comentario antes de ingresar un nuevo comentario
        comentario_field.clear()
        comentario_field.send_keys(f"Comentario para cliente {codigo}")
        print(f"Comentario ingresado para cliente {codigo}")
        time.sleep(2)  # Espera de 2 segundos

        # Toma una captura de pantalla después de ingresar los datos del cliente
        take_screenshot(driver, f"cliente_{codigo}")

finally:
    # Cierra el navegador
    driver.quit()
