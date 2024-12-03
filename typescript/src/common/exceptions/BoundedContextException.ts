export class BoundedContextException extends Error {
  protected _code: number = 0;

  constructor(message?: string) {
    super(message);
    this.name = 'BoundedContextException';
    Object.setPrototypeOf(this, new.target.prototype);
  }
  code(): number {
    return this._code;
  }
}
