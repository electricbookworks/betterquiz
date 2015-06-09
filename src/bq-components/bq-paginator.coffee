# BQPaginator is a very simple component that presents a pagination list.
# Is takes only a few attributes:
#   npages="total number of pages"
#   page="current page"
#   display="max number of pages to display"
#   On click, a callback is sent to any listeners (let's see if we can use something like
#   bricks Actions to make this sensible)
currentScript = document._currentScript || document.currentScript

class PageLink
  constructor: (paginator, text, n, current, enabled=true)->
    @el = document.createElement('li')
    if current
      @el.className = 'current'
    if not enabled
      @el.className = @el.className + " unavailable"
    @el.innerHTML=  '<a href="#">' + text + '</a>'
    @el.addEventListener('click', (evt)=>
      evt.stopPropagation()
      evt.preventDefault()
      if not current and enabled
        evt = new CustomEvent('bq-page', { 'bubbles':true, 'cancelable': true, 'detail' : { 'n' : n } } )
        @el.dispatchEvent(evt)
    )

class Paginator
  constructor: (@ul, @paginator)->
    @setup()
  setup: ()->
    @ul.removeChild(@ul.firstChild) while @ul.firstChild

    @pagesToShow = parseInt(@paginator.getAttribute("pages-to-show"))
    @current = parseInt(@paginator.getAttribute("current"))
    console.log?("In Paginator::setup - @current = #{@current}")

    @totalItems = parseInt(@paginator.getAttribute("total-items"))
    @itemsPerPage = parseInt(@paginator.getAttribute("items-per-page")) 
    [minPage, maxPage, lastPage] = @calculatePages(@current, @pagesToShow, @totalItems, @itemsPerPage)
    
    p = new PageLink(this, "&laquo;", @current-1, false, 0<@current)
    @ul.appendChild(p.el)

    for i in [minPage..maxPage]
      p = new PageLink(this, i+1, i, @current==i, true)
      @ul.appendChild(p.el)

    p = new PageLink(this, "&raquo;", @current+1, false, @current+1<lastPage)
    @ul.appendChild(p.el)

    @ul.addEventListener('bq-page', (evt)->
      console.log('Paginator::bq-page : pg=', evt.detail.n)
    )

  calculatePages: (current, pagesToShow, totalItems, itemsPerPage)->
    console.log(arguments)
    minPage = Math.max(0, current  - Math.floor(pagesToShow/2.0))
    console.log("minPage = #{minPage}")
    lastPage = Math.ceil(totalItems / (1.0*itemsPerPage)-1);
    console.log("lastPage = #{lastPage}")
    if ((minPage + pagesToShow) > lastPage)
      minPage = Math.max(0, lastPage - pagesToShow)
    maxPage = Math.min(minPage + pagesToShow, lastPage)
    return [minPage, maxPage, lastPage]

  # private function calculatePages($current, $pagesToShow, $items, $itemsPerPage) {
  #   $min = max(0, $current - floor($pagesToShow/2));
  #   $last = ceil($items / (1.0*$itemsPerPage));
  #   if (($min+$pagesToShow)>$last) {
  #     $min = max(0, $last - $pagesToShow);
  #   }
  #   $max = min($min + $pagesToShow, $last);
  #   return array($min, $max, $last);
  # }

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

BqPaginatorElementPrototype = Object.create(window.HTMLElement.prototype)

BqPaginatorElementPrototype.createdCallback = ()->
  window.HTMLElement?.prototype?.createdCallback?.call?(this)

  importDoc = currentScript.ownerDocument
  templateContent = importDoc.querySelector('#bq-paginator-template').content
  shimShadowStyles(templateContent.querySelectorAll('style'), 'bq-paginator')

  @shadowRoot = @createShadowRoot()
  @el = templateContent.cloneNode(true)
  @$ = {}
  for e in @el.querySelectorAll('[data-set]')
      @$[e.getAttribute("data-set")] = e
  @paginator = new Paginator(@$.ul, this)
  @shadowRoot.appendChild(@el)
  console.log("Completed bq-paginator createdCallback")
  return


attrs = {
  'current': (oldVal, newVal)->
    console.log("this = ", this)
    @paginator.setup()
}


BqPaginatorElementPrototype.attributeChangedCallback = (attr, oldVal, newVal)->
  attrs[attr]?.call?(this, oldVal, newVal)

propertyToAttribute = (attr)->
  {
    'get': ()->
      @getAttribute(attr)
    'set': (v)->
      @setAttribute(attr, v)
      return
  }

Object.defineProperties(BqPaginatorElementPrototype, {
  'current': propertyToAttribute('current')
})

window.BqPaginatorElement = document.registerElement('bq-paginator', {
  prototype: BqPaginatorElementPrototype
})
