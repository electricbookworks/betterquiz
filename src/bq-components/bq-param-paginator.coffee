
currentScript = document._currentScript || document.currentScript

class MyUrl
  constructor: (a=false)->
    if false==a
      a=window.location.search.substr(1).split('&')
    if ""==a
      return {}
    @qs = {}
    ((pair, b)->
        p = pair.split('=', 2)
        if 1==p.length
          b[p[0]] = ""
        else
          b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "))
    )(pair, @qs) for pair in a
  get: (key)->
    @qs[key]
  has: (key)->
    @qs[key]?
  set: (key, value)->
    @qs[key] = value
  url: ()->
    qry = (( encodeURIComponent(a) + "=" + encodeURIComponent(b) ) for a,b of @qs)
    window.location.origin + window.location.pathname + "?" + qry.join("&")

if !shimShadowStyles?
  shimShadowStyles = (styles, tag)->
    if !Platform.ShadowCSS
      return
    for style in styles
      do (style)->
        cssText = Platform.ShadowCSS.shimStyle(style,tag)
        Platform.ShadowCSS.addCssToDocument(cssText)
        style.remove()
        return
    return

BqParamPaginatorElementPrototype = Object.create(window.HTMLElement.prototype)

BqParamPaginatorElementPrototype.createdCallback = ()->
  window.HTMLElement?.prototype?.createdCallback?.call?(this)

  importDoc = currentScript.ownerDocument
  templateContent = importDoc.querySelector('#bq-param-paginator-template').content
  shimShadowStyles(templateContent.querySelectorAll('style'), 'bq-param-paginator')

  if 'function' == typeof @attachShadowRoot
    @shadowRoot = @attachShadowRoot({mode:'open'})
  else
    @shadowRoot = @createShadowRoot()

  @el = templateContent.cloneNode(true)
  @$ = {}
  attaches = @el.querySelectorAll('[data-set]')
  for i in [0...attaches.length]
    do (i)=>
      e = attaches.item(i)
      @$[e.getAttribute("data-set")] = e
      return

  @qs = new MyUrl()
  @param = @getAttribute("parameter")
  @paginator = document.getElementById(@getAttribute('paginator'))

  f = ()=>
    if 'complete'!=document.readyState
      return false
    # console.log('bq-param-paginator:: readystatechange : readyState=' + document.readyState)
    if @qs.has(@param)
      pg = parseInt(@qs.get(@param))
      @paginator.setAttribute('current', pg)

    @paginator.addEventListener('bq-page', (evt)=>
      # console.log('bq-param-paginator - page changed: ' , evt.detail.n)
      @qs.set(@param, evt.detail.n)
      # console.log('bq-param-paginator going to url: ' , @qs.url())
      window.location = @qs.url()
    )
    return true

  if !f()
    document.addEventListener('readystatechange', ()=>
      f()
    )
  # console.log('created bq-param-paginator createCallback exiting')
  return

BqParamPaginatorElementPrototype.attributeChangedCallback = (attr, oldVal, newVal)->
  attrs[attr]?.call?(this, oldVal, newVal)

attrs = {
  'example': (oldVal, newVal)->
}

propertyToAttribute = (attr)->
  {
    'get': ()->
      @getAttribute(attr)
    'set': (v)->
      @setAttribute(attr, v)
      return
  }

Object.defineProperties(BqParamPaginatorElementPrototype, {
  'example': propertyToAttribute('example')
})

window.BqParamPaginatorElement = document.registerElement('bq-param-paginator', {
  prototype: BqParamPaginatorElementPrototype
})
