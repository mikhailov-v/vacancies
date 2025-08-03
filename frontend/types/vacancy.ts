export interface Vacancy {
    id: number
    title: string
    description: string | null
    salary: number | null
    created_at: string
}

export interface VacancyFormData {
    title: string
    description: string
    salary: number | null
}

export interface VacancyTableOptions {
    page: number
    itemsPerPage: number
    sortBy: Array<{ key: string; order: 'asc' | 'desc' }>
}

export interface ValidationErrors {
    title: string[]
    description: string[]
    salary: string[]
}