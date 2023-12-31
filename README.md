# Question Bank

## Installation

1. Clone the repository

    ```bash
    git clone https://github.com/afeefiqbal/question_bank
    ```

2. Switch to the repo folder

    ```bash
    cd QuestionBank-Open-AI
    ```

3. Install all the dependencies using composer

    ```bash
    composer install
    ```

4. Copy the example env file and make the required configuration changes in the `.env` file

    ```bash
    cp .env.example .env
    ```

5. Add ChatGPT API key in the `.env` file

    ```env
    CHAT_GPT_KEY=####################
    ```

6. Generate a new application key

    ```bash
    php artisan key:generate
    ```

7. Run the database migrations (Set the database connection in `.env` before migrating)

    ```bash
    php artisan migrate
    ```

8. Start the local development server

    ```bash
    php artisan serve
    ```

9. You can now access the server at [http://localhost:8000](http://localhost:8000)

