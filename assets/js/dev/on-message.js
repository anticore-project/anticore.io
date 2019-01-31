import {anticore} from 'anticore'
import {one} from 'anticore/dom/query/one'
import {append} from 'anticore/dom/tree/append'

anticore.on('li.message', function (element, next, loaded, url) {
  loaded && append(element, one('[data-sse="' + url + '"]'))

  next()
})
