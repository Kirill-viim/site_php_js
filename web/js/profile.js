// js/Profile.jsx
'use strict';

Object.defineProperty(exports, '__esModule', {
  value: true
});

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _react = require('react');

var _react2 = _interopRequireDefault(_react);

var _reactDomClient = require('react-dom/client');

var _reactDomClient2 = _interopRequireDefault(_reactDomClient);

function Profile(_ref) {
  var userData = _ref.userData;

  if (!userData) {
    return _react2['default'].createElement(
      'div',
      null,
      'No profile data available.'
    );
  }

  if (userData.error) {
    return _react2['default'].createElement(
      'div',
      null,
      'Error: ',
      userData.error
    );
  }

  return _react2['default'].createElement(
    'div',
    { className: 'profile-container' },
    _react2['default'].createElement(
      'h1',
      null,
      'Profile Information'
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Surname:'
      ),
      ' ',
      userData.surname
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Name:'
      ),
      ' ',
      userData.name
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Email:'
      ),
      ' ',
      userData.email
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Phone:'
      ),
      ' ',
      userData.phone
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Course:'
      ),
      ' ',
      userData.course
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Department:'
      ),
      ' ',
      userData.department
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Favorite Object:'
      ),
      ' ',
      userData.fav_obj
    ),
    _react2['default'].createElement(
      'p',
      null,
      _react2['default'].createElement(
        'strong',
        null,
        'Additional Information:'
      ),
      ' ',
      userData.extra
    )
  );
}

window.renderProfile = function (containerId, userData) {
  var container = document.getElementById(containerId);
  if (container) {
    var root = _reactDomClient2['default'].createRoot(container);
    root.render(_react2['default'].createElement(Profile, { userData: userData }));
  } else {
    console.error('Container with id "' + containerId + '" not found.');
  }
};
exports['default'] = Profile;
module.exports = exports['default'];
