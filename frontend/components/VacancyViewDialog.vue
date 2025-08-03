<template>
  <v-dialog v-model="isOpen" max-width="900px">
    <v-card>
      <v-card-title>Просмотр вакансии</v-card-title>
      <v-card-text>
        <div v-if="loading" class="text-center py-4">
          <v-progress-circular indeterminate />
          <p class="mt-2">Загрузка данных...</p>
        </div>
        <div v-else-if="vacancy">
          <p><strong>Название:</strong> {{ vacancy.title }}</p>
          <p v-if="vacancy.salary">
            <strong>Зарплата:</strong> {{ formatSalary(vacancy.salary) }} ₽
          </p>
          <p><strong>Дата создания:</strong> {{ formatDate(vacancy.created_at) }}</p>
          <div v-if="vacancy.description">
            <v-divider class="mt-4 mb-4" />
            <div class="description-content">
              {{ vacancy.description }}
            </div>
          </div>
        </div>
        <div v-else class="text-center py-4">
          <p>Ошибка загрузки данных</p>
        </div>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn color="primary" @click="isOpen = false">Закрыть</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import type { Vacancy } from '~/types/vacancy'

interface Props {
  modelValue: boolean
  vacancyId: number | null
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()

const { fetchVacancy, formatSalary, formatDate } = useVacancies()

const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const vacancy = ref<Vacancy | null>(null)
const loading = ref(false)

const loadVacancy = async () => {
  if (!props.vacancyId) return

  loading.value = true
  vacancy.value = null

  try {
    vacancy.value = await fetchVacancy(props.vacancyId)
  } catch (error) {
    console.error('Ошибка загрузки вакансии:', error)
  } finally {
    loading.value = false
  }
}

watch(() => props.vacancyId, (newId) => {
  if (newId && isOpen.value) {
    loadVacancy()
  }
})

watch(isOpen, (newValue) => {
  if (newValue && props.vacancyId) {
    loadVacancy()
  } else if (!newValue) {
    vacancy.value = null
  }
})
</script>

<style scoped>
.description-content {
  white-space: pre-wrap;
  word-break: break-word;
}
</style>