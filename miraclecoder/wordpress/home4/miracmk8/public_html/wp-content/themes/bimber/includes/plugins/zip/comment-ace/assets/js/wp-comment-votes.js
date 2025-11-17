'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/* global ca */

(function () {
    /**
     * WordPress Comment Votes class
     */
    var WpCommentVotes = function () {
        function WpCommentVotes(props) {
            _classCallCheck(this, WpCommentVotes);

            this.comment = props.comment;
            this.commentId = props.commentId;
            this.props = props;

            this.init();
        }

        _createClass(WpCommentVotes, [{
            key: 'init',
            value: function init() {
                this.css = {
                    selectors: {
                        'wpComment': '.cace-comment-type-wp .comment-list .comment',
                        'votes': '.cace-comment-votes',
                        'score': '.cace-comment-score',
                        'scoreTotal': '.cace-comment-score-total',
                        'vote': '.cace-comment-vote',
                        'voteUp': '.cace-comment-vote-up',
                        'voteDown': '.cace-comment-vote-down',
                        'voteSelected': '.cace-comment-vote-selected'
                    },
                    classes: {
                        'scorePositive': 'cace-comment-score-positive',
                        'scoreNegative': 'cace-comment-score-negative',
                        'score0': 'cace-comment-score-0',
                        'vote': 'cace-comment-vote',
                        'voteUp': 'cace-comment-vote-up',
                        'voteDown': 'cace-comment-vote-down',
                        'voteSelected': 'cace-comment-vote-selected'
                    }
                };

                var _css = this.css,
                    selectors = _css.selectors,
                    classes = _css.classes;


                this.votes = this.comment.querySelector(selectors.votes);

                // Skip if there is no Votes container.
                if (!this.votes) {
                    return;
                }

                var selected = this.votes.querySelector(selectors.voteSelected);
                var selectedType = false;

                if (selected) {
                    selectedType = selected.classList.contains(classes.voteUp) ? 'up' : 'down';
                }

                var voteUpScore = this.votes.querySelector(selectors.voteUp).querySelector(selectors.score);
                var voteDownScore = this.votes.querySelector(selectors.voteDown).querySelector(selectors.score);

                this.state = {
                    upVotes: voteUpScore ? parseInt(voteUpScore.getAttribute('data-raw-value'), 10) : 0,
                    downVotes: voteDownScore ? parseInt(voteDownScore.getAttribute('data-raw-value'), 10) : 0,
                    selected: selectedType
                };

                this.bindEvents();
            }
        }, {
            key: 'bindEvents',
            value: function bindEvents() {
                var _this = this;

                var _css2 = this.css,
                    selectors = _css2.selectors,
                    classes = _css2.classes;


                this.votes.querySelectorAll(selectors.vote).forEach(function (elem) {
                    elem.addEventListener('click', function (e) {
                        var vote = e.currentTarget;

                        e.preventDefault();

                        if (!_this.isUserAllowedToVote()) {
                            // Snax integration.
                            if (!e.currentTarget.classList.contains('snax-login-required')) {
                                _this.redirectToLoginPage();
                            }

                            return false;
                        }

                        var voteType = vote.classList.contains(classes.voteUp) ? 'up' : 'down';

                        _this.vote(voteType);
                    });
                });
            }
        }, {
            key: 'voteUp',
            value: function voteUp() {
                var persist = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
                var selected = this.state.selected;

                var voteAdded = false;
                var voteRemoved = false;

                if (selected) {
                    if ('up' === selected) {
                        this.state.upVotes--;
                        this.state.selected = false;
                        voteRemoved = 'up';
                    } else {
                        this.state.upVotes++;
                        this.state.downVotes--;
                        this.state.selected = 'up';
                        voteAdded = 'up';
                        voteRemoved = 'down';
                    }
                } else {
                    this.state.upVotes++;
                    this.state.selected = 'up';
                    voteAdded = 'up';
                }

                this.render();

                if (persist) {
                    this.save(voteAdded, voteRemoved);
                }
            }
        }, {
            key: 'voteDown',
            value: function voteDown() {
                var persist = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
                var selected = this.state.selected;

                var voteAdded = false;
                var voteRemoved = false;

                if (selected) {
                    if ('down' === selected) {
                        this.state.downVotes--;
                        this.state.selected = false;
                        voteRemoved = 'down';
                    } else {
                        this.state.downVotes++;
                        this.state.upVotes--;
                        this.state.selected = 'down';
                        voteAdded = 'down';
                        voteRemoved = 'up';
                    }
                } else {
                    this.state.downVotes++;
                    this.state.selected = 'down';
                    voteAdded = 'down';
                }

                this.render();

                if (persist) {
                    this.save(voteAdded, voteRemoved);
                }
            }
        }, {
            key: 'vote',
            value: function vote(type) {
                var persist = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

                if ('up' === type) {
                    this.voteUp(persist);
                }

                if ('down' === type) {
                    this.voteDown(persist);
                }
            }

            // Upgrade DOM elements based on the state.

        }, {
            key: 'render',
            value: function render() {
                var _css3 = this.css,
                    selectors = _css3.selectors,
                    classes = _css3.classes;
                var _state = this.state,
                    upVotes = _state.upVotes,
                    downVotes = _state.downVotes,
                    selected = _state.selected;

                var scoreValue = upVotes - downVotes;

                // Update total score.
                var score = this.votes.querySelector(selectors.scoreTotal);

                score.setAttribute('data-raw-value', scoreValue);

                score.classList.remove(classes.score0, classes.scoreNegative, classes.scorePositive);

                if (0 < scoreValue) {
                    score.classList.add(classes.scorePositive);
                } else if (0 > scoreValue) {
                    score.classList.add(classes.scoreNegative);
                } else {
                    score.classList.add(classes.score0);
                }

                score.textContent = ca.numberFormatI18N(scoreValue);

                // Update up votes.
                var scoreUp = this.votes.querySelector(selectors.voteUp).querySelector(selectors.score);

                if (scoreUp) {
                    upVotes ? scoreUp.classList.remove(classes.score0) : scoreUp.classList.add(classes.score0);

                    scoreUp.setAttribute('data-raw-value', upVotes);
                    scoreUp.textContent = ca.numberFormatI18N(upVotes);
                }

                // Update down votes.
                var scoreDown = this.votes.querySelector(selectors.voteDown).querySelector(selectors.score);

                if (scoreDown) {
                    downVotes ? scoreDown.classList.remove(classes.score0) : scoreDown.classList.add(classes.score0);

                    scoreDown.setAttribute('data-raw-value', downVotes);
                    scoreDown.textContent = ca.numberFormatI18N(downVotes);
                }

                // Update buttons UI.
                var voteSelected = this.votes.querySelector(selectors.voteSelected);

                if (voteSelected) {
                    voteSelected.classList.remove(classes.voteSelected);
                }

                if (selected) {
                    switch (selected) {
                        case 'up':
                            this.votes.querySelector(selectors.voteUp).classList.add(classes.voteSelected);
                            break;

                        case 'down':
                            this.votes.querySelector(selectors.voteDown).classList.add(classes.voteSelected);
                            break;
                    }
                }
            }

            // Store date in database and/or local storage.

        }, {
            key: 'save',
            value: function save(voteAdded, voteRemoved) {
                var _this2 = this;

                var requestData = {
                    comment_id: this.commentId,
                    vote_added: voteAdded ? voteAdded : '',
                    vote_removed: voteRemoved ? voteRemoved : ''
                };

                var params = new window.URLSearchParams(requestData);
                var url = ca.ajax_url + '?action=commentace_vote&security=' + ca.nonce;

                fetch(url, {
                    method: 'POST',
                    cache: 'no-cache',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: params.toString()
                }).then(function (response) {
                    return response.json();
                }).then(function (response) {
                    if ('success' !== response.status) {
                        // Revert state.
                        if (voteAdded) {
                            'up' === voteAdded ? _this2.state.upVotes-- : _this2.state.downVotes--;
                        }

                        if (voteRemoved) {
                            'up' === voteRemoved ? _this2.state.upVotes++ : _this2.state.downVotes++;
                        }

                        _this2.render();

                        if (!ca.isUserLoggedIn()) {
                            // Clear the stored value.
                            ca.WpCommentGuestVoteStorage.set(_this2.commentId, null);
                        }
                    }
                });

                // Store guest vote in localStorage.
                if (!ca.isUserLoggedIn()) {
                    ca.WpCommentGuestVoteStorage.set(this.commentId, null);

                    if (voteAdded) {
                        ca.WpCommentGuestVoteStorage.set(this.commentId, voteAdded);
                    }
                }
            }
        }, {
            key: 'guestCanVote',
            value: function guestCanVote() {
                return this.props.guestCanVote;
            }
        }, {
            key: 'isUserAllowedToVote',
            value: function isUserAllowedToVote() {
                return ca.isUserLoggedIn() || this.guestCanVote();
            }
        }, {
            key: 'redirectToLoginPage',
            value: function redirectToLoginPage() {
                window.location.href = ca.login_url;
            }
        }]);

        return WpCommentVotes;
    }();

    ca.WpCommentVotes = WpCommentVotes;
})();

(function () {
    /**
     * WordPress Comment Guest Vote Storage class
     */
    var WpCommentGuestVoteStorage = function () {
        function WpCommentGuestVoteStorage() {
            _classCallCheck(this, WpCommentGuestVoteStorage);
        }

        _createClass(WpCommentGuestVoteStorage, null, [{
            key: 'set',
            value: function set(commentId, type) {
                try {
                    var votes = localStorage.getItem('caceGuestVotes');

                    if (!votes) {
                        votes = {};
                    } else {
                        // Decode.
                        votes = JSON.parse(votes);
                    }

                    // Remove old value, if exists.
                    if (votes[commentId]) {
                        delete votes[commentId];
                    }

                    // Set new value, if provided.
                    if (type) {
                        votes[commentId] = type;
                    }

                    // Update storage.
                    localStorage.setItem('caceGuestVotes', JSON.stringify(votes));
                } catch (e) {
                    ca.error(e);
                }
            }
        }, {
            key: 'get',
            value: function get(commentId) {
                try {
                    var votes = localStorage.getItem('caceGuestVotes');

                    if (!votes) {
                        return null;
                    } else {
                        votes = JSON.parse(votes);
                    }

                    if (!votes[commentId]) {
                        return null;
                    }

                    return votes[commentId];
                } catch (e) {
                    ca.error(e);
                }

                return null;
            }
        }]);

        return WpCommentGuestVoteStorage;
    }();

    ca.WpCommentGuestVoteStorage = WpCommentGuestVoteStorage;
})();