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
    # Abre la página de inicio de sesión
    driver.get("http://localhost:3000/login.php")
    print("Página de inicio de sesión abierta")
    time.sleep(2)  # Espera de 2 segundos

    # Espera hasta que el campo de nombre de usuario esté presente
    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CSS_SELECTOR, "#username"))
    )
    print("Campo de nombre de usuario encontrado")
    time.sleep(2)  # Espera de 2 segundos

    # Encuentra el campo de nombre de usuario y envía un nombre de usuario incorrecto
    username_field = driver.find_element(By.CSS_SELECTOR, "#username")
    username_field.send_keys("wronguser")
    print("Nombre de usuario incorrecto ingresado")
    time.sleep(2)  # Espera de 2 segundos

    # Encuentra el campo de contraseña y envía una contraseña incorrecta
    password_field = driver.find_element(By.CSS_SELECTOR, "#password")
    password_field.send_keys("wrongpass")
    print("Contraseña incorrecta ingresada")
    time.sleep(2)  # Espera de 2 segundos

    # Encuentra el botón de inicio de sesión y haz clic en él
    try:
        login_button = WebDriverWait(driver, 10).until(
            EC.element_to_be_clickable((By.CSS_SELECTOR, ".login-button"))
        )
        login_button.click()
        print("Botón de inicio de sesión clickeado")
    except TimeoutException:
        print("Tiempo de espera agotado para el botón de inicio de sesión")
        driver.quit()
        exit()

    time.sleep(2)  # Espera de 2 segundos

    # Verifica si el mensaje de error se muestra
    try:
        error_message = WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, ".error-message"))
        )
        print("Inicio de sesión fallido: mensaje de error mostrado")

        # Toma una captura de pantalla cuando el mensaje de error se muestra
        take_screenshot(driver, "login_failed")

    except TimeoutException:
        print("Tiempo de espera agotado para la verificación del mensaje de error")
        driver.quit()
        exit()

finally:
    # Cierra el navegador
    driver.quit()
