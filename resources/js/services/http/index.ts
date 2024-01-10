import type {RequestMiddleware, ResponseErrorMiddleware, ResponseMiddleware} from './types';
import type {AxiosRequestConfig} from 'axios';

import axios from 'axios';

const baseURL = '/api';

const http = axios.create({
    baseURL,
    withCredentials: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

const requestMiddleware: RequestMiddleware[] = [];
const responseMiddleware: ResponseMiddleware[] = [];
const responseErrorMiddleware: ResponseErrorMiddleware[] = [];

http.interceptors.request.use(request => {
    for (const middleware of requestMiddleware) middleware(request);

    return request;
});

http.interceptors.response.use(
    response => {
        for (const middleware of responseMiddleware) middleware(response);

        return response;
    },
    // eslint-disable-next-line promise/prefer-await-to-callbacks
    error => {
        if (!error.response) return Promise.reject(error);
        for (const middleware of responseErrorMiddleware) middleware(error);

        return Promise.reject(error);
    },
);

// Set browser token.
export const setToken = (token?: string | null) => {
    if (token) {
        localStorage.setItem('token', token);
        http.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        return;
    }

    localStorage.removeItem('token');
    delete http.defaults.headers.common['Authorization'];
    delete http.defaults.headers['Authorization'];

    http.defaults.withCredentials = false;
};

export const setPublicCode = (code?: string | null) => {
    if (code) {
        localStorage.setItem('Code', code);
        http.defaults.headers.common['Code'] = code;
        return;
    }

    localStorage.removeItem('Code');
    delete http.defaults.headers.common.Code;
};

export const getCodeFromLocalStorage = () => {
    return localStorage.getItem('Code');
};

export const getToken = () => {
    return localStorage.getItem('token');
};

/**
 * send a get request to the given endpoint
 */
export const getRequest = (endpoint: string, options?: AxiosRequestConfig) => http.get(endpoint, options);

/**
 * send a post request to the given endpoint with the given data
 */
export const postRequest = (endpoint: string, data: unknown, options?: AxiosRequestConfig) =>
    http.post(endpoint, data, options);

/**
 * send a post request with a file.
 */
export const postFileRequest = (endpoint: string, file: File, options: any = {headers: {}}) => {
    // const formData = new FormData();
    // formData.append('file', file);

    const requestOptions = {
        ...options,
        headers: {
            ...http.defaults.headers,
            'Content-Type': 'multipart/form-data',
            ...options.headers,
        },
    };

    return http.post(endpoint, {file: file}, requestOptions);
};
/**
 * send a put request to the given endpoint with the given data
 */
export const putRequest = (endpoint: string, data: unknown) => http.put(endpoint, data);

/**
 * send a delete request to the given endpoint
 */
export const deleteRequest = (endpoint: string) => http.delete(endpoint);

export const registerRequestMiddleware = (middlewareFunc: RequestMiddleware) => requestMiddleware.push(middlewareFunc);
export const registerResponseMiddleware = (middlewareFunc: ResponseMiddleware) =>
    responseMiddleware.push(middlewareFunc);
export const registerResponseErrorMiddleware = (middlewareFunc: ResponseErrorMiddleware) =>
    responseErrorMiddleware.push(middlewareFunc);
