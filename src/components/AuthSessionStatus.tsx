import { HTMLAttributes } from 'react'

interface Props extends HTMLAttributes<HTMLDivElement> {
  className?: string
  status?: string | null
}

function AuthSessionStatus({ status, className, ...props }: Props) {
  if (status) {
    return (
      <div
        className={`${className} font-medium text-sm text-green-600 dark:text-green-400`}
        {...props}>
        {status}
      </div>
    )
  }

  return null
}

export default AuthSessionStatus
