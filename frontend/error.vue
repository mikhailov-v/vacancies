<template>
  <v-app>
    <v-main>
      <v-container class="text-center">
        <div class="py-16">
          <h1 class="text-h2 mb-4">{{ error.statusCode }}</h1>
          <p class="text-h5 mb-8">{{ error.statusMessage }}</p>
          <v-btn color="primary" @click="handleError">
            Вернуться на главную
          </v-btn>
        </div>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
interface NuxtError {
  statusCode: number
  statusMessage: string
}

const props = defineProps<{
  error: NuxtError
}>()

const handleError = async () => {
  await clearError({ redirect: '/' })
}
</script>