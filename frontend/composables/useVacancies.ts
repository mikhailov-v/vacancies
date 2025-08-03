/**
 * Композабл для работы с вакансиями
 */
export const useVacancies = () => {
    const config = useRuntimeConfig()
    const baseUrl = process.server ? config.apiBaseUrl : config.public.apiBaseUrl

    const formatSalary = (salary: number | null): string => {
        return salary ? salary.toLocaleString('ru-RU') : '—'
    }

    const formatDate = (dateStr: string | null): string => {
        if (!dateStr) return '—'
        return new Date(dateStr).toLocaleDateString('ru-RU', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        })
    }

    const fetchVacancies = async (options: {
        page: number
        itemsPerPage: number
        sortBy: Array<{ key: string; order: 'asc' | 'desc' }>
    }) => {
        let sortParam = ''
        if (options.sortBy.length > 0) {
            const s = options.sortBy[0]
            sortParam = `${s.order === 'desc' ? '-' : ''}${s.key}`
        }

        const url = `${baseUrl}/vacancy?page=${options.page}&per-page=${options.itemsPerPage}` +
            (sortParam ? `&sort=${sortParam}` : '')

        const res = await $fetch.raw(url)
        const total = res.headers.get('x-pagination-total-count')

        return {
            vacancies: res._data,
            total: total ? parseInt(total, 10) : 0
        }
    }

    const fetchVacancy = async (id: number) => {
        return await $fetch(`${baseUrl}/vacancy/${id}`)
    }

    const createVacancy = async (data: {
        title: string
        description: string
        salary: number | null
    }) => {
        return await $fetch(`${baseUrl}/vacancy`, {
            method: 'POST',
            body: data
        })
    }

    return {
        formatSalary,
        formatDate,
        fetchVacancies,
        fetchVacancy,
        createVacancy
    }
}