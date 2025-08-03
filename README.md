# Проект "Вакансии" (Nuxt 3 + Yii2 API)

Демонстрационный проект для управления вакансиями.  
Frontend — **Nuxt 3 + Vuetify**.  
Backend — **Yii2 REST API**.  
Запуск в **Docker**.

---

## 📦 Возможности

- Просмотр списка вакансий с пагинацией и сортировкой
- Просмотр подробной информации о вакансии
- Добавление новой вакансии
- Автоматическая подгрузка вакансий без перезагрузки страницы
- Тестовые данные для демонстрации

---

## 🚀 Быстрый старт

### 1. Клонировать репозиторий
```bash
git clone git@github.com:mikhailov-v/vacancies.git vacancies && cd vacancies
```

### 2. Запустить проект в Docker
```bash
docker compose up -d --build
```

### 3. Установить зависимости Yii2
```bash
docker compose exec php composer install
```

### 4. Выполнить первичную настройку БД
```bash
docker compose exec php php yii migrate --interactive=0
```

### 5. Открыть проект
[http://localhost:3000](http://localhost:3000)
