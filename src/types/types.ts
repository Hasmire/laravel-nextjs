// SAMPLE TYPES
export type ErrorObject = {
  email?: string[]
  password?: string[]
  password_confirmation?: string[]
}

export type ApiResponse<T> = {
  success: boolean
  data: T
}

export type PaginationData<T> = {
  items: T[]
  totalItems: number
  totalPages: number
  currentPage: number
}

export type UserDetails = {
  id: number
  name: string
  email: string
}

export type UserSettings = {
  darkMode: boolean
  notifications: boolean
}
