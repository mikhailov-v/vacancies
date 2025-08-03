<template>
  <v-data-table-server
      :headers="headers"
      :items="vacancies"
      :items-length="totalItems"
      :items-per-page="itemsPerPage"
      :loading="loading"
      class="elevation-1"
      @update:options="$emit('update:options', $event)"
      @click:row="(_, row) => $emit('view-vacancy', row.item)"
  >
    <template #item.index="{ index }">
      {{ (options.page - 1) * itemsPerPage + index + 1 }}
    </template>

    <template #item.salary="{ item }">
      {{ formatSalary(item.salary) + (item.salary ? ' ₽' : '') }}
    </template>

    <template #item.created_at="{ item }">
      {{ formatDate(item.created_at) }}
    </template>

    <template #item.description="{ item }">
      <span class="truncate-text" :title="item.description || ''">
        {{ item.description || '—' }}
      </span>
    </template>
  </v-data-table-server>
</template>

<script setup lang="ts">
import type { Vacancy, VacancyTableOptions } from '~/types/vacancy'

interface Props {
  vacancies: Vacancy[]
  totalItems: number
  loading: boolean
  options: VacancyTableOptions
}

defineProps<Props>()

defineEmits<{
  'update:options': [options: VacancyTableOptions]
  'view-vacancy': [vacancy: Vacancy]
}>()

const { formatSalary, formatDate } = useVacancies()

const itemsPerPage = 10

const headers = [
  { title: '#', key: 'index', sortable: false },
  { title: 'Название', key: 'title', sortable: true },
  { title: 'Описание', key: 'description', sortable: false },
  { title: 'Зарплата', key: 'salary', sortable: true },
  { title: 'Дата создания', key: 'created_at', sortable: true }
]
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