'use strict';

describe('Service: storeActivityLog', function () {

  // load the service's module
  beforeEach(module('coreApiApp'));

  // instantiate service
  var storeActivityLog;
  beforeEach(inject(function (_storeActivityLog_) {
    storeActivityLog = _storeActivityLog_;
  }));

  it('should do something', function () {
    expect(!!storeActivityLog).toBe(true);
  });

});
