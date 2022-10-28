#!/usr/bin/env zx
const dontenv = require('dotenv')
const env = dontenv.config({ path: '.env.prod' })

const main = async () => {
  const { parsed: envs } = env
  for (const [key, value] of Object.entries(envs)) {
    await $`heroku config:set ${key}=${value}`
  }
}

main()