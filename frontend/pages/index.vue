<template>
  <v-container>
    <!-- Заголовок и кнопка -->
    <div class="d-flex justify-space-between align-center mb-4">
      <h1 class="text-h5">Вакансии</h1>
      <v-btn color="primary" @click="dialogAdd = true">Добавить вакансию</v-btn>
    </div>

    <!-- Таблица вакансий -->
    <v-data-table-server
        :headers="headers"
        :items="vacancies"
        :items-length="totalItems"
        :items-per-page="itemsPerPage"
        :loading="loading"
        class="elevation-1"
        @update:options="updateOptions"
        @click:row="(_, row) => viewVacancy(row.item)"
    >
      <template #item.index="{ index }">
        {{ (options.page - 1) * itemsPerPage + index + 1 }}
      </template>

      <template #item.salary="{ item }">
        {{ item.salary ? formatSalary(item.salary) + ' ₽' : '' }}
      </template>

      <template #item.created_at="{ item }">
        {{ formatDate(item.created_at) }}
      </template>

      <template #item.description="{ item }">
        <span class="truncate-text" :title="item.description">
          {{ item.description }}
        </span>
      </template>
    </v-data-table-server>

    <!-- Модалка добавления вакансии -->
    <v-dialog v-model="dialogAdd" max-width="900px">
      <v-card>
        <v-card-title>Новая вакансия</v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="isFormValid">
            <v-text-field
                label="Название"
                v-model="formData.title"
                :error-messages="errors.title"
                required
            />
            <v-textarea
                label="Описание"
                v-model="formData.description"
                :error-messages="errors.description"
            />
            <v-text-field
                label="Зарплата (₽)"
                type="number"
                v-model.number="formData.salary"
                :error-messages="errors.salary"
            />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn text @click="dialogAdd = false">Отмена</v-btn>
          <v-btn color="primary" @click="saveVacancy">Сохранить</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Модалка просмотра вакансии -->
    <v-dialog v-model="dialogView" max-width="900px">
      <v-card>
        <v-card-title>Просмотр вакансии</v-card-title>
        <v-card-text v-if="selectedVacancy">
          <p><strong>Название:</strong> {{ selectedVacancy.title }}</p>
          <p v-if="selectedVacancy.salary">
            <strong>Зарплата:</strong> {{ formatSalary(selectedVacancy.salary) }} ₽
          </p>
          <p><strong>Дата создания:</strong> {{ formatDate(selectedVacancy.created_at) }}</p>
          <div v-if="selectedVacancy.description">
            <v-divider class="mt-4 mb-4"></v-divider>
            {{ selectedVacancy.description }}
          </div>
        </v-card-text>
        <v-card-text v-else>
          Загрузка данных...
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="primary" @click="dialogView = false">Закрыть</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, computed } from 'vue'

const config = useRuntimeConfig()
const baseUrl = process.server ? config.apiBaseUrl : config.public.apiBaseUrl

// Заголовки таблицы
const headers = [
  { title: '#', key: 'index', sortable: false },
  { title: 'Название', key: 'title', sortable: true },
  { title: 'Описание', key: 'description', sortable: false },
  { title: 'Зарплата', key: 'salary', sortable: true },
  { title: 'Дата создания', key: 'created_at', sortable: true }
]

// Параметры таблицы
const itemsPerPage = 10
const options = ref({ page: 1, itemsPerPage, sortBy: [] })

// Загружаем вакансии через useAsyncData (SSR + CSR)
const { data, refresh, pending } = await useAsyncData('vacancies', async () => {
  let sortParam = ''
  if (options.value.sortBy.length > 0) {
    const s = options.value.sortBy[0]
    sortParam = `${s.order === 'desc' ? '-' : ''}${s.key}`
  }
  const url =
      `${baseUrl}/vacancy?page=${options.value.page}&per-page=${options.value.itemsPerPage}` +
      (sortParam ? `&sort=${sortParam}` : '')

  const res = await $fetch.raw(url)
  const total = res.headers.get('x-pagination-total-count')

  return {
    vacancies: res._data,
    total: total ? parseInt(total, 10) : 0
  }
})

// Привязка данных
const vacancies = computed(() => data.value?.vacancies || [])
const totalItems = computed(() => data.value?.total || 0)
const loading = computed(() => pending.value)

// Обновление параметров таблицы
function updateOptions(newOptions) {
  options.value = newOptions
  refresh()
}

// Форматирование
function formatSalary(salary) {
  return typeof salary === 'number' ? salary.toLocaleString('ru-RU') : '—'
}
function formatDate(dateStr) {
  if (!dateStr) return '—'
  return new Date(dateStr).toLocaleDateString('ru-RU', {
    year: 'numeric', month: '2-digit', day: '2-digit'
  })
}

// ===== Модалка добавления =====
const dialogAdd = ref(false)
const isFormValid = ref(false)
const formData = ref({
  title: '',
  description: '',
  salary: null
})
const errors = ref({
  title: [],
  description: [],
  salary: []
})

async function saveVacancy() {
  errors.value = { title: [], description: [], salary: [] }
  try {
    await $fetch(`${baseUrl}/vacancy`, {
      method: 'POST',
      body: formData.value
    })
    dialogAdd.value = false
    formData.value = { title: '', description: '', salary: null }
    refresh()
  } catch (err) {
    if (err?.data?.errors) {
      for (const field in err.data.errors) {
        errors.value[field] = err.data.errors[field]
      }
    } else {
      console.error(err)
    }
  }
}

// ===== Модалка просмотра =====
const dialogView = ref(false)
const selectedVacancy = ref(null)
function viewVacancy(item) {
  if (!item?.id) return
  dialogView.value = true
  selectedVacancy.value = null
  $fetch(`${baseUrl}/vacancy/${item.id}`)
      .then(data => { selectedVacancy.value = data })
      .catch(console.error)
}
</script>

<style scoped>
.truncate-text {
  display: inline-block;
  max-width: 600px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  vertical-align: bottom;
}
</style>
