expectEvent = (
  eventName,
  component = null,
  eventType = 'Fire',
  expectPresent = true
) => {
  if (component == null) component = window.vm

  component.$nextTick(() => {
    var expectedEvent = eventName

    var eventInHistory = EventBus[`get${eventType}History`]().filter(
      e => e == expectedEvent
    )
    if (expectPresent) return expect(expectedEvent).toEqual(eventInHistory[0])

    return expect(eventInHistory).toEqual([])
  })
}

notExpectEvent = (eventName, component = null, eventType = 'Fire') => {
  return expectEvent(eventName, component, eventType, false)
}
expectListenEvent = (eventName, component = null) => {
  return expectEvent(eventName, component, 'Listen')
}

createVue = (props = null, component = null) => {
  if (component == null) component = window.specComponent

  let Ctor = Vue.extend(component)

  window.vm = new Ctor({
    propsData: props
  }).$mount()

  window.vm.then = then

  return window.vm
}

then = (callback, data = null, component = null) => {
  if (component == null) component = window.vm

  component.$nextTick(() => {
    callback(data)
  })
  return { then: then, next: then }
}

spy = (method, component = null) => {
  if (component == null) component = window.vm

  sinonSandbox.spy(component, method)
}

window.submitFailedRequest = (response, target = 'parameters') => {
  moxios.stubRequest(window.Laravel.base_url + target, {
    status: 422,
    response: response
  })

  window.vm.submit()

  return {
    then: callback => {
      then(() => {
        moxios.wait(() => {
          callback()
        })
      })
    }
  }
}
