import {
  useEffect,
  useState,
  FormEventHandler,
  SetStateAction,
  Dispatch,
} from 'react'

import { useRouter } from 'next/router'

import AuthCard from '../../components/AuthCard'
import AuthSessionStatus from '../../components/AuthSessionStatus'
import Input from '../../components/Input'
import InputError from '../../components/InputError'
import Label from '../../components/Label'
import GuestLayout from '../../components/Layouts/GuestLayout'
import PrimaryButton from '../../components/PrimaryButton'
import { useAuth } from '../../hooks/auth'

function PasswordReset() {
  const { query } = useRouter()

  const { resetPassword } = useAuth({ middleware: 'guest' })

  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [passwordConfirmation, setPasswordConfirmation] = useState('')
  const [errors, setErrors] = useState<any>([])
  const [status, setStatus] = useState(null)

  const submitForm: FormEventHandler = event => {
    event.preventDefault()

    resetPassword({
      email,
      password,
      password_confirmation: passwordConfirmation,
      setErrors: setErrors as Dispatch<SetStateAction<never[]>>,
      setStatus,
    })
  }

  useEffect(() => {
    const mail = query && query.email ? (query.email as string) : ''

    setEmail(mail)
  }, [query.email])

  return (
    <GuestLayout>
      <AuthCard>
        {/* Session Status */}
        <AuthSessionStatus className="mb-4" status={status} />

        <form onSubmit={submitForm}>
          {/* Email Address */}
          <div>
            <Label htmlFor="email">Email</Label>

            <Input
              id="email"
              type="email"
              value={email}
              className="block mt-1 w-full"
              onChange={event => setEmail(event.target.value)}
              required
              autoFocus
            />

            <InputError messages={errors.email} className="mt-2" />
          </div>

          {/* Password */}
          <div className="mt-4">
            <Label htmlFor="password">Password</Label>
            <Input
              id="password"
              type="password"
              value={password}
              className="block mt-1 w-full"
              onChange={event => setPassword(event.target.value)}
              required
            />

            <InputError messages={errors.password} className="mt-2" />
          </div>

          {/* Confirm Password */}
          <div className="mt-4">
            <Label htmlFor="passwordConfirmation">Confirm Password</Label>

            <Input
              id="passwordConfirmation"
              type="password"
              value={passwordConfirmation}
              className="block mt-1 w-full"
              onChange={event => setPasswordConfirmation(event.target.value)}
              required
            />

            <InputError
              messages={errors.password_confirmation}
              className="mt-2"
            />
          </div>

          <div className="flex items-center justify-end mt-4">
            <PrimaryButton>Reset Password</PrimaryButton>
          </div>
        </form>
      </AuthCard>
    </GuestLayout>
  )
}

export default PasswordReset
