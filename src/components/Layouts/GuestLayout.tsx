import { PropsWithChildren } from 'react'

import Head from 'next/head'

function GuestLayout({ children }: PropsWithChildren) {
  return (
    <div>
      <Head>
        <title>Laravel</title>
      </Head>

      <div className="font-sans text-gray-900 antialiased">{children}</div>
    </div>
  )
}

export default GuestLayout
