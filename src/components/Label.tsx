import { PropsWithChildren, LabelHTMLAttributes } from 'react'

function Label({
  className,
  children,
  htmlFor,
  ...props
}: PropsWithChildren<LabelHTMLAttributes<HTMLLabelElement>>) {
  return (
    <label
      {...props}
      htmlFor={htmlFor}
      className={`block font-medium text-sm text-gray-700 dark:text-gray-300 ${className}`}>
      {children}
    </label>
  )
}

export default Label
