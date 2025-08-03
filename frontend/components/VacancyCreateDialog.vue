<template>
  <v-dialog v-model="isOpen" max-width="900px">
    <v-card>
      <v-card-title>Новая вакансия</v-card-title>
      <v-card-text>
        <v-form ref="formRef" v-model="isFormValid">
          <v-text-field
              label="Название"
              v-model="formData.title"
              :error-messages="errors.title"
              :rules="[rules.required]"
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
              :rules="[rules.positiveNumber]"
          />
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn text @click="closeDialog">Отмена</v-btn>
        <v-btn
            color="primary"
            @click="handleSave"
            :loading="saving"
            :disabled="!isFormValid"
        >
          Сохранить
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import type { VacancyFormData, ValidationErrors } from '~/types/vacancy'

interface Props {
  modelValue: boolean
}

const props = defineProps<Props>()

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
  'vacancy-created': []
}>()

const { createVacancy } = useVacancies()

const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const formRef = ref()
const isFormValid = ref(false)
const saving = ref(false)

const formData = ref<VacancyFormData>({
  title: '',
  description: '',
  salary: null
})

const errors = ref<ValidationErrors>({
  title: [],
  description: [],
  salary: []
})

const rules = {
  required: (value: string) => !!value || 'Поле обязательно для заполнения',
  positiveNumber: (value: number | null) => {
    if (value === null || value === undefined) return true
    return value > 0 || 'Значение должно быть положительным'
  }
}

const resetForm = () => {
  formData.value = {
    title: '',
    description: '',
    salary: null
  }
  errors.value = {
    title: [],
    description: [],
    salary: []
  }
  formRef.value?.resetValidation()
}

const closeDialog = () => {
  isOpen.value = false
  resetForm()
}

const handleSave = async () => {
  if (!isFormValid.value) return

  saving.value = true
  errors.value = { title: [], description: [], salary: [] }

  try {
    await createVacancy(formData.value)
    emit('vacancy-created')
    closeDialog()
  } catch (err: any) {
    if (err?.data?.errors) {
      for (const field in err.data.errors) {
        if (field in errors.value) {
          errors.value[field as keyof ValidationErrors] = err.data.errors[field]
        }
      }
    } else {
      console.error('Ошибка при создании вакансии:', err)
    }
  } finally {
    saving.value = false
  }
}

watch(isOpen, (newValue) => {
  if (!newValue) {
    resetForm()
  }
})
</script>