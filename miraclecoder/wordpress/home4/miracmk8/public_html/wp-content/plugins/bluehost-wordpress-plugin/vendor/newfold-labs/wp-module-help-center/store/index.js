import { configureStore } from '@reduxjs/toolkit';
import helpcenter from '../store/helpcenterSlice';

export const store = configureStore( {
	reducer: {
		helpcenter,
	},
} );
