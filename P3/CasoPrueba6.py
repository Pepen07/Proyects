import time
import os  # Importar la biblioteca os para manejo de archivos
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.action_chains import ActionChains
from selenium.common.exceptions import UnexpectedAlertPresentException, NoAlertPresentException

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
    # Abre la página de registro de clientes
    driver.get("http://localhost:3000/registro_clientes.php")
    print("Página de registro de clientes abierta")
    time.sleep(2)  # Espera de 2 segundos

    # Lista de códigos y nombres de cliente
    clientes = [
        ("2023-1001", "Cliente Uno"),
        ("2023-1002", "Cliente Dos")
    ]

    for codigo, nombre in clientes:
        # Encuentra y llena el campo de código de cliente
        codigo_cliente_field = driver.find_element(By.CSS_SELECTOR, "#codigoCliente")
        codigo_cliente_field.clear()
        codigo_cliente_field.send_keys(codigo)
        print(f"Código del cliente {codigo} ingresado")
        time.sleep(2)  # Espera de 2 segundos

        # Encuentra y llena el campo de nombre de cliente
        nombre_cliente_field = driver.find_element(By.CSS_SELECTOR, "#nombreCliente")
        nombre_cliente_field.clear()
        nombre_cliente_field.send_keys(nombre)
        print(f"Nombre del cliente {nombre} ingresado")
        time.sleep(2)  # Espera de 2 segundos

        # Envía el formulario
        submit_button = driver.find_element(By.CSS_SELECTOR, "#clientForm button[type='submit']")
        submit_button.click()
        print(f"Cliente {nombre} registrado")
        time.sleep(2)  # Espera de 2 segundos para el registro

        # Maneja la alerta si aparece
        try:
            alert = driver.switch_to.alert
            print(f"Alerta detectada: {alert.text}")
            alert.accept()  # Cierra la alerta
            print("Alerta cerrada")
        except NoAlertPresentException:
            print("No se detectó ninguna alerta")

        # Toma una captura de pantalla después de registrar el cliente
        take_screenshot(driver, f"cliente_{codigo}")

        # Refresca la página
        driver.refresh()
        print("Página refrescada")
        time.sleep(2)  # Espera de 2 segundos

        # Desplaza hacia abajo hasta el listado de clientes
        actions = ActionChains(driver)
        listado_clientes = driver.find_element(By.CSS_SELECTOR, ".table-responsive")
        actions.move_to_element(listado_clientes).perform()
        print("Desplazado hasta el listado de clientes")
        time.sleep(2)  # Espera de 2 segundos para observar el listado

finally:
    # Cierra el navegador
    driver.quit()
