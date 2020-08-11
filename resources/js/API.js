// const HOST = ''
export const WEP_URL_PLATFORMS = '/platforms';

export const API_GET_PLATFORMS = '/platforms/list';
export const API_CRUD_PLATFORM = '/platforms';

export const API_BOXES_RESOURCE = '/platforms/boxes';  //{boxid}
export const API_GET_PLATFORM_CREATE_DEFAULT_BOXES = '/platforms/boxes/create-default'; //{platformId}

export const API_GET_KUPONS = '/platforms/boxes/kupons/list';
export const API_CRUD_KUPONS = '/platforms/boxes/kupons';  //{kuponid}
export const API_MASSDELETE_KUPONS = '/platforms/boxes/kupons-delete';

export const API_MASS_DELETE_KUPONS_IN_BOX = '/platforms/boxes/delete-all-kupons';  //{boxId}

export const API_GET_STAT_NUMBERS = '/platforms/statistics/all-numbers';  // {platformId}

export const API_POST_SEND_QUESTION = '/send-question';