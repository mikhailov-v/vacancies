<template>
  <v-container>
    <!-- Заголовок и кнопка -->
    <div class="d-flex justify-space-between align-center mb-4">
      <h1 class="text-h5">Вакансии</h1>
      <v-btn color="primary" @click="showCreateDialog = true">
        Добавить вакансию
      </v-btn>
    </div>

    <!-- Таблица вакансий -->
    <VacancyTable
        :vacancies="vacancies"
        :total-items="totalItems"
        :loading="loading"
        :options="options"
        @update:options="updateOptions"
        @view-vacancy="viewVacancy"
    />

    <!-- Модалка добавления вакансии -->
    <VacancyCreateDialog
        v-model="showCreateDialog"
        @vacancy-created="handleVacancyCreated"
    />

    <!-- Модалка просмотра вакансии -->
    <VacancyViewDialog
        v-model="showViewDialog"
        :vacancy-id="selectedVacancyId"
    />
  </v-container>
</template>

<script setup lang="ts">
import type { Vacancy, VacancyTableOptions } from '~/types/vacancy'

const { fetchVacancies } = useVacancies()

// Параметры таблицы
const options = ref<VacancyTableOptions>({
  page: 1,
  itemsPerPage: 10,
  sortBy: []
})

// Загружаем вакансии через useAsyncData (SSR + CSR)
const { data, refresh, pending } = await useAsyncData('vacancies', () =>
    fetchVacancies(options.value)
)

// Привязка данных
const vacancies = computed(() => data.value?.vacancies || [])
const totalItems = computed(() => data.value?.total || 0)
const loading = computed(() => pending.value)

// Обновление параметров таблицы
const updateOptions = (newOptions: VacancyTableOptions) => {
  options.value = newOptions
  refresh()
}

// Модалки
const showCreateDialog = ref(false)
const showViewDialog = ref(false)
const selectedVacancyId = ref<number | null>(null)

const handleVacancyCreated = () => {
  refresh()
}

const viewVacancy = (vacancy: Vacancy) => {
  selectedVacancyId.value = vacancy.id
  showViewDialog.value = true
}

useHead({
  title: 'Вакансии',
  meta: [
    { name: 'description', content: 'Список доступных вакансий' }
  ]
})
</script>