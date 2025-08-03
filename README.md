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
git https://github.com/mikhailov-v/vacancies
cd vacancies
```

### 2. Запустить проект в Docker
```bash
docker compose up -d --build
```

### 3. Выполнить первичную настройку БД
```bash
docker compose exec php php yii migrate --interactive=0
```

### 4. Открыть проект
[http://localhost:3000](http://localhost:3000)
