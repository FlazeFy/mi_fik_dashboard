function getDateToContext(datetime, type){
    if(datetime){
        const result = new Date(datetime);

        if(type == "full"){
            const now = new Date(Date.now());
            const yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            
            //FIx this!!!
            if(result.toDateString() === now.toDateString()){
                // $start_date = new DateTime(datetime);
                // $since_start = $start_date->diff(new DateTime(Date.now()));

                // if(result.getHours() == now.getHours()){
                //     const min = result.getMinutes() - now.getMinutes();
                //     if(min <= 10 && min > 0){
                //         return $since_start->m;
                //     } else {
                //         return  min + " minutes ago";    
                //     }
                // } else if(now.getHours() - result.getHours() <= 6){
                //     return now.getHours() - result.getHours() + " hours ago";    
                // } else {
                    return " Today at " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
                //}
            } else if(result.toDateString() === yesterday.toDateString()){
                return " Yesterday at " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
            } else {
                return " " + result.getFullYear() + "/" + (result.getMonth() + 1) + "/" + ("0" + result.getDate()).slice(-2) + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);  
            }
        } else if(type == "24h"){
            return ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);
        } else if(type == "12h"){
            return ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2); // Check this
        } else if(type == "datetime"){
            return " " + result.getFullYear() + "/" + (result.getMonth() + 1) + "/" + ("0" + result.getDate()).slice(-2) + " " + ("0" + result.getHours()).slice(-2) + ":" + ("0" + result.getMinutes()).slice(-2);  
        }
    } else {
        return "-";
    }
}