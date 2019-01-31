import {anticore} from 'anticore'

anticore.on('[data-sse]', function (element, next) {
  anticore.sse(element.dataset.sse)

  next()
})
