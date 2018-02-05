import { createApp } from './app'

export default context => {
  return new Promise((resolve, reject) => {
    const { app, router } = createApp()
    router.push(context.url)
    router.onReady(() => {
      const matchedComponents = router.getMatchedComponents()
      if (!matchedComponents.length) {
        if (typeof httpServer !== 'undefined') {
          httpServer.res.status(404)
        }
        // else {
        //   return reject({ code: 404 })
        // }
      }
      resolve(app)
    }, reject)
  })
}
