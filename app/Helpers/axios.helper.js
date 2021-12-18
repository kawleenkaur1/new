const axios=require('axios');

const makeRequest = async (method='get',url,headers={},data={}) => {

    const config = {
        method: method,
        url: url,
        headers: headers,
        data:data
    }

    let res = await axios(config).then(rs=>rs.data)

    return res
    // console.log(res.request._header);
}

// const makeRequest = (method='get',url,headers,data) => {
//     return new Promise(async (resolve,reject)=>{
//         await axios({
//             method: method,
//             url: url,
//             headers: headers,
//             data:data
//         }).then((response)=>resolve(response.data)).catch((e)=>reject(alert(e.message)))
//     })
// }

module.exports = {
    makeRequest
}