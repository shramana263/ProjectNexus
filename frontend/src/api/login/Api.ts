import { apiDictionary, ApiDictionary } from "../ApiDict"
import axios from 'axios';

export const sendOtp = async (data: ApiDictionary['auth']['sendOtp']['body']) => {
    return await axios({
        url: apiDictionary['auth']['sendOtp']['url'],
        method:    apiDictionary['auth']['sendOtp']['method'],
        headers: {
            'Content-Type': 'application/json'
        },
        data: data
    });
}
export const register = async (data: ApiDictionary['auth']['register']['body']) => {
    return await axios({
        url: apiDictionary['auth']['register']['url'],
        method: apiDictionary['auth']['register']['method'],
        headers: {
            'Content-Type': 'application/json'
        },
        data: data
    });
}
export const login = async (data: ApiDictionary['auth']['login']['body']) => {
    return await axios({
        url: apiDictionary['auth']['login']['url'],
        method: apiDictionary['auth']['login']['method'],
        headers: {
            'Content-Type': 'application/json'
        },
        data: data
    });
}