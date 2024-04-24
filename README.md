# La sonrisa de Deva - Backend
Servidor del proyecto La sonrisa de Deva

## 📄 Descripción
Este servidor es responsable de gestionar la autenticación, los productos, los eventos y la seguridad de la aplicación de La Sonrisa de Deva. Hace uso de Laravel Breeze para proporcionar una autenticación simple y fácilmente escalable. Utiliza MySQL para almacenar los datos de manera persistente y Cloudinary para gestionar las imágenes de los productos.

## 📚 Instalación

1. **Clona el repositorio**:

```bash
git clone https://github.com/KalypsoDev/sonrisa-deva-back
```

2. **Instalación de dependencias**:

Asegúrate de tener PHP y Composer instalados en tu sistema.
En la terminal, navega hasta la carpeta del proyecto y ejecuta:

```bash
composer install
```

3. **Configuración del entorno**:

Crea o modifica un archivo .env en la raíz del proyecto y configura las variables de entorno necesarias, como la conexión a la  base de datos MySQL y las credenciales de Cloudinary.

4. **Configuración de la base de datos**:

Crea una base de datos MySQL para el proyecto y configura las credenciales en el archivo .env.

5. **Ejecuta las migraciones para crear las tablas necesarias en la base de datos**:
```bash
php artisan migrate
```

6. **Ejecución del servidor:**

```bash
php artisan serve
```

Esto iniciará el servidor. Asegúrate de que esté funcionando correctamente antes de usar la aplicación frontend.


## 📕 Rutas

#### Autenticación

- `/register` (POST): Registro de nuevos usuarios. 
- `/login` (POST): Inicio de sesión de Administrador.
- `/logout` (POST): Cierre de sesión de Administrador.

#### Productos

- `/products` (GET): Obtener todos los productos.
- `/products/{id}` (GET): Obtener un producto específico por ID.
- `/products` (POST): Crear un nuevo producto.
- `/products/{id}` (POST): Actualizar un producto existente por ID.
- `/products/{id}` (DELETE): Eliminar un producto existente por ID.

#### Eventos

- `/events` (GET): Obtener todos los eventos.
- `/events/{id}` (GET): Obtener un evento específico por ID.
- `/events` (POST): Crear un nuevo evento.
- `/events/{id}` (POST): Actualizar un evento existente por ID.
- `/events/{id}` (DELETE): Eliminar un evento existente por ID.

> [!NOTE]  
> Las siguientes Rutas estan implementadas y testeadas con postman, pero no asociadas en el Front-End de momento

#### Clientes

- `/customers` (GET): Obtener todos los clientes.
- `/customers/{id}` (GET): Obtener un cliente específico por ID.
- `/customers` (POST): Crear un nuevo cliente.

#### Pedidos

- `/orders` (GET): Obtener todos los pedidos.
- `/orders/{id}` (GET): Obtener un pedido específico por ID.
- `/orders` (POST): Crear un nuevo pedido.
- `/orders/cancelled/{id}` (PUT): Actualizar el estado de un pedido a cancelado por ID.

#### Productos del Pedido

- `/order-products` (GET): Obtener todos los productos de los pedidos.
- `/order-products/{id}` (GET): Obtener un producto de pedido específico por ID.
- `/order-products` (POST): Crear un nuevo producto de pedido.
- `/order-products/orders/{id}` (PUT): Actualizar el estado y el stock de un producto de pedido por ID.

## 💻 Tecnologías Utilizadas

- Laravel
- Sanctum
- MySQL
- Cloudinary

## 👩‍💻 Autoras
| ![Ana Cecilia](https://avatars.githubusercontent.com/AnaCe-7?s=50) | ![Claudia](https://avatars.githubusercontent.com/claudiaglez?s=50) | ![Desiree](https://avatars.githubusercontent.com/DevDesiree?s=50) | ![Angela](https://avatars.githubusercontent.com/KalypsoDev?s=50) | ![Yami](https://avatars.githubusercontent.com/yamiranea?s=50) |
| --- | --- | --- | --- | --- |
| Ana Cecilia | Claudia Gónzalez | Desire Sánchez | Angela Ántunez | Yami Ranea |
| [GitHub](https://github.com/AnaCe-7) | [GitHub](https://github.com/claudiaglez) | [GitHub](https://github.com/DevDesiree) | [GitHub](https://github.com/KalypsoDev) | [GitHub](https://github.com/yamiranea) |
| [LinkedIn](https://www.linkedin.com/in/ana-cecilia-reques/) | [LinkedIn](https://www.linkedin.com/in/claudiaglezgarcia/) | [LinkedIn](https://www.linkedin.com/in/desisanchez/) | [LinkedIn](https://www.linkedin.com/in/angela-antunez-sanchez/) | [LinkedIn](https://www.linkedin.com/in/yamila-ranea/)