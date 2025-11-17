import { createSlice } from '@reduxjs/toolkit';

const initialState = {
	visible: false,
	helpEnabled: false,
	noResult: false,
	isNewResult: false,
	searchInput: '',
	isLoading: false,
	loadingQuery: null,
	loadingIndex: null,
	resultContent: [],
	multiResults: {},
	showSuggestions: false,
	initComplete: false,
	disliked: false,
	isFooterVisible: true,
	helpResultHistory: [],
	triggerSearch: false,
	showBackButton: false,
	viaLinkSearch: [],
	hasLaunchedFromTooltip: false,
};

const helpcenterSlice = createSlice({
	name: 'helpcenter',
	initialState,
	reducers: {
		setIsTooltipLoading: (state) => {
			state.isLoading = true;
			state.isFooterVisible = false;
			state.hasLaunchedFromTooltip = true;
		},
		updateIsTooltipLoading: (state) => {
			state.isLoading = false;
		},
		clearViaLinkSearch: (state) => {
			state.showBackButton = false;
			state.viaLinkSearch = [];
		},
		initialDataSet: (state, action) => {
			state.isFooterVisible = action.payload.isFooterVisible;
			state.searchInput = action.payload.SearchInput;
		},
		updateHelpResultHistoryFromDB: (state, action) => {
			state.helpResultHistory = action.payload;
		},
		updateHelpResultHistory: (state, action) => {
			const isAlreadyInHistory = state.helpResultHistory.some(
				(item) => item.postId === action.payload.postId
			);

			if (!isAlreadyInHistory) {
				if (state.helpResultHistory.length === 3) {
					state.helpResultHistory.shift();
				}
				state.helpResultHistory.push(action.payload);
			}
			if (!state.searchInput) {
				if (state.viaLinkSearch.length === 10) {
					state.viaLinkSearch.shift();
				}
				state.viaLinkSearch.push(action.payload);
			}
		},
		setDisliked: (state, action) => {
			state.disliked = action.payload;
		},
		setFeeback: (state, action) => {
			const index = state.helpResultHistory.findIndex(
				(item) => item.postId === action.payload.postId
			);
			if (index >= 0) {
				state.helpResultHistory[index].feedbackSubmitted =
					action.payload.feedbackStatus;
			}
		},
		setIsFooterVisible: (state, action) => {
			state.isFooterVisible = action.payload;
		},
		setNoResult: (state) => {
			state.noResult = true;
			state.isFooterVisible = true;
		},
		updateHelpEnabled: (state, action) => {
			state.helpEnabled = action.payload;
		},
		updateVisibility: (state, action) => {
			state.visible = action.payload;
		},
		updateResultContent: (state, action) => {
			state.noResult = false;
			state.resultContent = action.payload;
			state.viaLinkSearch.push(action.payload);
			state.showBackButton = true;
			state.isFooterVisible = false;
		},
		resetState: (state) => {
			state.resultContent = [];
			state.disliked = false;
			state.noResult = false;
			state.viaLinkSearch = [];
			state.showBackButton = false;
			state.hasLaunchedFromTooltip = false;
		},
		setNewSearchResult: (state, action) => {
			state.isNewResult = action.payload;
			state.searchInput = '';
		},
		updateMultiResults: (state, action) => {
			state.multiResults = action.payload.results;
			state.showSuggestions = action.payload.suggestions;
		},
		updateInitComplete: (state, action) => {
			state.initComplete = action.payload;
		},
		updateSearchInput: (state, action) => {
			state.noResult = false;
			state.errorMsg = '';
			state.searchInput = action.payload;
			state.hasLaunchedFromTooltip = false;
		},
		searchInputCatch: (state) => {
			state.noResult = true;
			state.isNewResult = true;
			state.isFooterVisible = true;
		},
		searchInputFinally: (state) => {
			state.searchInput = '';
			state.isLoading = false;
			state.loadingIndex = null;
			state.showSuggestions = false;
		},
		setAIResultLoading: (state) => {
			state.isLoading = true;
			state.showSuggestions = false;
			state.loadingQuery = state.searchInput;
		},
		setTriggerSearch: (state, action) => {
			state.triggerSearch = action.payload;
		},
		goBackInHistory: (state) => {
			if (state.hasLaunchedFromTooltip) {
				state.hasLaunchedFromTooltip = false;
			}
			if (state.viaLinkSearch.length >= 1) {
				state.viaLinkSearch.pop();
				state.resultContent =
					state.viaLinkSearch[state.viaLinkSearch.length - 1];
				state.isNewResult = false;
			}

			if (state.viaLinkSearch.length < 1) {
				state.showBackButton = false;
				state.resultContent = [];
				state.disliked = false;
				state.noResult = false;
				state.viaLinkSearch = [];
				state.isFooterVisible = true;
			}

			if (state.hasLaunchedFromTooltip) {
				state.hasLaunchedFromTooltip = false;
			}
		},
		setShowBackButton: (state, action) => {
			state.showBackButton = action.payload;
		},
	},
});

export const helpcenterActions = helpcenterSlice.actions;

export default helpcenterSlice.reducer;
