import React, { useState } from 'react'
import { login, sendOtp } from '../../../api/login/Api'

const Login = () => {
  const [email, setEmail] = useState<string>('')
  const [password, setPassword] = useState<string>('')
  const onClick= async()=>{
    // send otp 
    const data = {
      email: email,
      password: password
    }
    const responseData = await login(data)
    // register 
    console.log('email',email)
    console.log('password',password)
    console.log('data',responseData)  
  }
  return (
    <div>
      <h1>Login</h1>
      <p>Enter your email and password</p>
      <input type="text"  value={email} onChange={(e)=>{setEmail((e.target as HTMLInputElement).value)}}/>
      <input type="text"  value={password} onChange={(e)=>{setPassword((e.target as HTMLInputElement).value)}}/>
      <button onClick={onClick}>Login</button>
    </div>
  )
}

export default Login