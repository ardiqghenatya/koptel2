'use strict';

describe('Controller: ActvityLogCtrl', function () {

  // load the controller's module
  beforeEach(module('coreApiApp'));

  var ActvityLogCtrl,
    scope;

  // Initialize the controller and a mock scope
  beforeEach(inject(function ($controller, $rootScope) {
    scope = $rootScope.$new();
    ActvityLogCtrl = $controller('ActvityLogCtrl', {
      $scope: scope
    });
  }));

  it('should attach a list of awesomeThings to the scope', function () {
    expect(scope.awesomeThings.length).toBe(3);
  });
});
