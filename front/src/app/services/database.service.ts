import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {environment} from "../../environments/environment";
import {Observable, of} from "rxjs/index";
import {catchError} from "rxjs/internal/operators";

const httpOptions = {
    headers: new HttpHeaders({'Content-Type': 'application/json'})
};

export interface TweetObject {
    sets: object [];
    labels: object [];
}

@Injectable({
    providedIn: 'root'
})
export class DatabaseService {

    constructor(private http: HttpClient) {
    }

    getTweets(): Observable<TweetObject> {
        return this.http.get(`${environment.api_url}/tweets`, httpOptions)
            .pipe(
                catchError(this.handleError<any>('tweets', []))
            )
    }

    private handleError<T>(operation = 'operation', result?: T) {
        return (error: any): Observable<T> => {
            console.log(`failed: ${error.message}`);
            return of(result as T);
        };
    }
}
