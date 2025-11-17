import { addQueryArgs } from '@wordpress/url';
import { default as Notification } from '../notification/';

/**
 * Notifications Component
 * For use in brand app to display notifications
 * 
 * Notification shape:
 * [
 *  {
 *      id: (string) "ID",
 *      expiration: (Number) Date.time() 
 *      locations: (Array) [
 *          {
 *              context: (string) 'wp-admin-notice' || 'brand-plugin' 
 *              pages: (string) 'pagename' || (Array) [ 'page1', 'page2' ]
 *          }
 *      ],
 *      content: (html) HTML_CONTENT - (escaped)
 *  },
 *  ...
 * ]
 * 
 * @param {*} props 
 * @returns 
 */
const Notifications = ({methods, constants, ...props}) => {
	const [ activeNotifications, setActiveNotifications ] = methods.useState( [] );
    const [ allNotifications, setAllNotifications ] = methods.useState( [] );
    
    // on mount load all notifications from module api
    methods.useEffect(() => {
        methods.apiFetch( {
            url: addQueryArgs(
                `${window.NewfoldRuntime.restUrl}newfold-notifications/v1/notifications`, 
                { context: constants.context }
            )
        }).then( ( response ) => {
            setAllNotifications(response);
        });
    }, [] );

    // on update notifications, context or page calculate active notifications
    methods.useEffect(() => {
        setActiveNotifications(
            filterNotifications(allNotifications)
        );
    }, [allNotifications, constants.page]);

    /**
     * Wrapper method to filter notifications
     * @param Array notifications array of unfiltered notifications
     * @returns Array of filtered notifications
     */
    const filterNotifications = (notifications) => {
        return (
            // last check pages
            filterByLocationPages(
                // then check context
                filterByLocationContext(
                    // first check expiration
                    filterByExpiry(
                        notifications
                    )
                )
            )
        );
    };
    
    /**
     * Filter specific to expirations
     * @param Array notifications array of unfiltered notifications
     * @returns Array of filtered notifications - removes expired notifications
     */
    const filterByExpiry = (notifications) => {
        const now = Math.round(Date.now() / 1000);
        // console.log( 'Now: ' + now );
        // filter out expired notifications
        return methods.filter(notifications, 
            (notification) => { 
                // console.log( notification.expiration > now ? 
                //     notification.id + ' is not yet expired. Still ' + ( notification.expiration - now ) + 'ms' :
                //     notification.id + ' is an expired notification. Expired for ' + ( notification.expiration - now ) + 'ms'
                // );
                return notification.expiration > now;
            }
        );
    };
    
    /**
     * Filter specific to locations context
     * @param Array notifications array of unfiltered notifications
     * @returns Array of filtered notifications - removes unmatched contexts
     */
    const filterByLocationContext = (notifications) => {
        // console.log('Filtering by location context. Matching context:' + constants.context );
        return methods.filter(notifications, 
            (notification) => {
                // console.log( notification.locations[0].context === constants.context ?
                //     constants.context + ': context match' :
                //     constants.context + ': not matching context'
                // );
                var isContextMatch = false;
                notification.locations.forEach(location => {
                    if ( location.context === 'wp-plugin-search' || location.context === 'wp-theme-search' ) {
                        isContextMatch = false;
                        return;
                    }
                    if ( location.context === constants.context ) {
                        isContextMatch = true;
                    }
                });
                return isContextMatch;
            }
        );
    };

    /**
     * Filter specific to locations pages
     * @param Array notifications array of unfiltered notifications
     * @returns Array of filtered notifications - removes unmatched pages
     */
    const filterByLocationPages = (notifications) => {
        // console.log('Filtering by location pages. Matching page:' + constants.page );
        return methods.filter(notifications, 
            (notification) => {
                var isPageMatch = false;
                notification.locations.forEach(location => {
                    // pages is string
                    if ( typeof location.pages === 'string' ) {
                        // pages matches current page or is `all`
                        if ( location.pages === constants.page || location.pages === 'all' ) {
                            isPageMatch = true;
                        }
                    }
                    // pages is array and contains current page
                    if ( Array.isArray(location.pages) && location.pages.includes( constants.page ) ) {
                        isPageMatch = true;
                    }
                });
                return isPageMatch;
            }
        );
    }

    methods.removeNotification = (id) => {
        setAllNotifications(
            methods.filter(allNotifications,
                (notification) => {
                    // console.log('Removing notification with id:',id);
                    return notification.id !== id;
                }
            )
        );
    }

    if (`${window.NewfoldRuntime.plugin.brand}-app-nav` === constants.context && activeNotifications.length > 0) {
        return (
            <div className={'newfold-nav-notifications-wrapper nfd-mt-4'}>
                <Notification 
                    id={activeNotifications[0].id} 
                    key={activeNotifications[0].id}
                    content={activeNotifications[0].content}
                    constants={constants}
                    methods={methods}
                />
            </div>
        );
    } else {
        return (
            <div className={'newfold-notifications-wrapper'}>
                {activeNotifications.map(notification => (
                    <Notification 
                        id={notification.id} 
                        key={notification.id}
                        content={notification.content}
                        constants={constants}
                        methods={methods}
                    />
                ))}
            </div>
        );
    }

};

export default Notifications;