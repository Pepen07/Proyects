import time
import os
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

def tomar_captura_pantalla(driver, nombre_archivo):
    """
    Toma una captura de pantalla y la guarda en el archivo especificado.

    :param driver: Instancia del navegador de Selenium.
    :param nombre_archivo: Ruta y nombre del archivo donde guardar la captura.
    """
    driver.save_screenshot(nombre_archivo)
    print(f"Captura de pantalla guardada en: {nombre_archivo}")

def login(username, password):
    # Inicializa el navegador
    driver = webdriver.Chrome(service=driver_service, options=chrome_options)

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

        # Encuentra el campo de nombre de usuario y envía el valor
        username_field = driver.find_element(By.CSS_SELECTOR, "#username")
        username_field.send_keys(username)
        print("Nombre de usuario ingresado")
        time.sleep(2)  # Espera de 2 segundos

        # Encuentra el campo de contraseña y envía el valor
        password_field = driver.find_element(By.CSS_SELECTOR, "#password")
        password_field.send_keys(password)
        print("Contraseña ingresada")
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

        time.sleep(3)  # Espera de 3 segundos

        # Verifica el tipo de usuario y realiza la navegación
        if username == "admin":
            # Navegar por el navbar como admin
            navigate_admin(driver)
        elif username == "empleado":
            # Asegúrate de que solo accede al menú de estadísticas
            navigate_empleado(driver)

    finally:
        # Cierra el navegador
        driver.quit()

def navigate_admin(driver):
    # Navegar a los distintos apartados como admin
    try:
        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "a[href='Factura.php']"))
        ).click()
        print("Navegación a 'Registrar Factura' completada")
        time.sleep(2)
        tomar_captura_pantalla(driver, "admin_factura.png")

        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "a[href='registro_clientes.php']"))
        ).click()
        print("Navegación a 'Registrar Cliente' completada")
        time.sleep(2)
        tomar_captura_pantalla(driver, "admin_clientes.png")

        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "a[href='registro_articulos.php']"))
        ).click()
        print("Navegación a 'Registrar Artículo' completada")
        time.sleep(2)
        tomar_captura_pantalla(driver, "admin_articulos.png")

        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "a[href='menu.php']"))
        ).click()
        print("Navegación a 'Estadísticas' completada")
        time.sleep(2)
        tomar_captura_pantalla(driver, "admin_estadisticas.png")

    except TimeoutException:
        print("Tiempo de espera agotado para la navegación del admin")
        driver.quit()
        exit()

def navigate_empleado(driver):
    # Asegúrate de que el empleado solo puede acceder al menú de estadísticas
    try:
        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "a[href='menu.php']"))
        ).click()
        print("Navegación a 'Estadísticas' completada")
        time.sleep(2)
        tomar_captura_pantalla(driver, "empleado_estadisticas.png")

    except TimeoutException:
        print("Tiempo de espera agotado para la navegación del empleado")
        driver.quit()
        exit()

# Login como admin
login("admin", "admin")

# Login como empleado
login("empleado", "empleado")
