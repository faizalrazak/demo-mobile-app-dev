## Steps to Run This System

1. **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/my-cinema-app.git
    cd my-cinema-app
    ```

2. **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3. **Copy and configure environment file:**
    ```bash
    cp .env.example .env
    ```
    Edit `.env` and set your database credentials.

4. **Generate application key:**
    ```bash
    php artisan key:generate
    ```

5. **Run database migrations:**
    ```bash
    php artisan migrate
    ```

6. **Seed the database (optional):**
    ```bash
    php artisan db:seed
    ```
    This will populate your database with sample data if you have defined seeders.

7. **Start the development server:**
    ```bash
    php artisan serve
    ```

8. **(Optional) Build frontend assets:**
    ```bash
    npm run dev
    ```

Your Laravel app should now be running at [http://localhost:8000](http://localhost:8000).
