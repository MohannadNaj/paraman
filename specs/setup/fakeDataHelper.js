appCategoryStub = {
  target: null,
  blocked: false,
  title: 'Category',
  parameters: [],
  isCategoriesGroup: false
}

appCategory = (mergeData, count = 1) => {
  appCategories = []

  for (var i = 0; i < count; i++) {
    appCategories.push(_.extend(appCategoryStub, mergeData))
  }

  return count == 1 ? _.clone(appCategories[0]) : appCategories
}

setUpCategories = () => {
  TestData.categories.forEach(_category => {
    var category = appCategory({
      title: _category.value,
      target: _category.id,
      parameters: TestData.categorized_parameters.filter(
        param => param.category_id == _category.id
      )
    })
    vm.categories.push(category)
  })
}
