export enum Method {
  GET = "GET",
  POST = "POST",
  PUT = "PUT",
  PATCH = "PATCH",
  DELETE = "DELETE",
}
export class FrameworkRequest {
  readonly method: Method;
  readonly path: string;
  readonly param: any;
  readonly body: any;
  readonly headers: any;


  constructor(method: Method, fullPath: string, body: any, headers: any
  ) {
    this.method = method;
    this.path = fullPath
    this.param =
    this.body = body
    this.headers = headers
  }

  public pathStart(): string {
    return this.path.substring(0, this.path.lastIndexOf('/'));
  }

  public getIdPath(): string {
    return this.path.substring(this.path.lastIndexOf('/') + 1);
  }
}
