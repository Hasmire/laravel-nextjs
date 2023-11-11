import React, { PropsWithChildren, ButtonHTMLAttributes } from 'react'

import { Menu } from '@headlessui/react'
import Link, { LinkProps } from 'next/link'

function DropdownLink({ children, ...props }: PropsWithChildren<LinkProps>) {
  return (
    <Menu.Item>
      {({ active }) => (
        <Link
          {...props}
          className={`block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out ${
            active ? 'bg-gray-100 dark:bg-gray-800' : ''
          }`}>
          {children}
        </Link>
      )}
    </Menu.Item>
  )
}

export function DropdownButton({
  children,
  ...props
}: PropsWithChildren<ButtonHTMLAttributes<HTMLButtonElement>>) {
  return (
    <Menu.Item>
      {({ active }) => (
        // eslint-disable-next-line react/button-has-type
        <button
          className={`block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out ${
            active ? 'bg-gray-100 dark:bg-gray-800' : ''
          }`}
          {...props}>
          {children}
        </button>
      )}
    </Menu.Item>
  )
}

export default DropdownLink
