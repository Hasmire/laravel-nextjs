import useSWR from 'swr'
import { useEffect, Dispatch, SetStateAction } from 'react'
import { useRouter } from 'next/router'
import axios, { csrf } from '../lib/axios'

declare type AuthMiddleware = 'auth' | 'guest'

interface IUseAuth {
  middleware: AuthMiddleware
  redirectIfAuthenticated?: string
}

interface IApiRequest {
  setErrors: Dispatch<SetStateAction<never[]>>
  setStatus: Dispatch<SetStateAction<any | null>>
  [key: string]: any
}

export interface User {
  id?: number
  name?: string
  email?: string
  email_verified_at?: string
  must_verify_email?: boolean // this is custom attribute
  created_at?: string
  updated_at?: string
}

export const useAuth = ({ middleware, redirectIfAuthenticated }: IUseAuth) => {
  const router = useRouter()

  const {
    data: user,
    error,
    mutate,
  } = useSWR<User>('/api/user', () =>
    axios
      .get('/api/user')
      .then(res => res.data)
      .catch(err => {
        if (err.response.status !== 409) throw err

        router.push('/verify-email')
      }),
  )

  const register = async (args: IApiRequest) => {
    const { setErrors, ...props } = args

    await csrf()

    setErrors([])

    axios
      .post('/register', props)
      .then(() => mutate())
      .catch(err => {
        if (err.response.status !== 422) throw err

        setErrors(err.response.data.errors)
      })
  }

  const login = async (args: IApiRequest) => {
    const { setErrors, setStatus, ...props } = args

    await csrf()

    setErrors([])
    setStatus(null)

    axios
      .post('/login', props)
      .then(() => mutate())
      .catch(err => {
        if (err.response.status !== 422) throw err
        setErrors(err.response.data.errors)
      })
  }

  const forgotPassword = async (args: IApiRequest) => {
    const { setErrors, setStatus, email } = args
    await csrf()

    setErrors([])
    setStatus(null)

    axios
      .post('/forgot-password', { email })
      .then(response => setStatus(response.data.status))
      .catch(err => {
        if (err.response.status !== 422) throw err

        setErrors(err.response.data.errors)
      })
  }

  const resetPassword = async (args: IApiRequest) => {
    const { setErrors, setStatus, ...props } = args
    await csrf()

    setErrors([])
    setStatus(null)

    axios
      .post('/reset-password', { token: router.query.token, ...props })
      .then(response =>
        router.push(`/login?reset=${btoa(response.data.status)}`),
      )
      .catch(err => {
        if (err.response.status !== 422) throw err

        setErrors(err.response.data.errors)
      })
  }

  const resendEmailVerification = (args: IApiRequest) => {
    const { setStatus } = args

    axios
      .post('/email/verification-notification')
      .then(response => setStatus(response.data.status))
  }

  const logout = async () => {
    if (!error) {
      await axios.post('/logout').then(() => mutate())
    }

    window.location.pathname = '/login'
  }

  useEffect(() => {
    if (middleware === 'guest' && redirectIfAuthenticated && user)
      router.push(redirectIfAuthenticated)
    if (window.location.pathname === '/verify-email' && user?.email_verified_at)
      router.push(redirectIfAuthenticated || '/')
    if (middleware === 'auth' && error) logout()
  }, [user, error])

  return {
    user,
    register,
    login,
    forgotPassword,
    resetPassword,
    resendEmailVerification,
    logout,
  }
}
